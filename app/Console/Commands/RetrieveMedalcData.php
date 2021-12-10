<?php

namespace App\Console\Commands;

use App\Device;
use App\HealthFacility;
use App\MedicalStaff;
use App\Services\DeviceService;
use App\Services\HealthFacilityService;
use App\Services\Http;
use App\Services\MedicalStaffService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use stdClass;

class RetrieveMedalcData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medalc:retrieve_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command overwrites health facility, device and medical staff tables and retrieves data from medal-c.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns JSON data from medal-c
     */
    private function getMedalCData($study_id)
    {

        $url = Config::get('medal.creator.url') . Config::get('medal.creator.get_from_study') . $study_id;
        try {
            $response = Http::get($url);
            return json_decode($response['content']);
        } catch (Exception $e) {
            $this->error('Could not retrieve data at url ' . $url . ': ' . $e);
        }
    }

    private static function getHealthFacilityData($data)
    {
        return [
            'id' => $data->id,
            'long' => $data->longitude,
            'lat' => $data->latitude,
            'hf_mode' => $data->architecture,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
            'name' => $data->name,
            'country' => $data->country,
            'area' => $data->area,
            'local_data_ip' => $data->local_data_ip,
            'pin_code' => $data->pin_code,
        ];
    }

    private static function getDevicesData($data)
    {
        return $data->devices;
    }

    private static function getMedicalStaffsData($data)
    {
        return $data->medical_staffs;
    }

    private static function addHealthFacilityToDB($health_facility_data)
    {
        $health_facility = new HealthFacility();
        $health_facility->group_id = $health_facility_data['id'];
        $health_facility->long = $health_facility_data['long'] ?? 0.0;
        $health_facility->lat = $health_facility_data['lat'] ?? 0.0;
        $health_facility->hf_mode = $health_facility_data['hf_mode'];
        $health_facility->name = $health_facility_data['name'];
        $health_facility->country = $health_facility_data['country'];
        $health_facility->area = $health_facility_data['area'];
        $local_data_ip = $health_facility_data['local_data_ip'];
        $health_facility->local_data_ip = $local_data_ip === '' ? null : $local_data_ip;
        $health_facility->pin_code = $health_facility_data['pin_code'];
        $health_facility->save();

        return $health_facility;
    }

    private static function addDeviceToDB($health_facility_service, $device_service, $health_facility, $device_data, $user_id)
    {
        $device_request = [
            'id' => $device_data->id ?? null,
            'name' => $device_data->name ?? 'device',
            'type' => $device_data->type ?? 'reader',
            'mac_address' => $device_data->mac_address ?? null,
            'model' => $device_data->model ?? null,
            'brand' => $device_data->brand ?? null,
            'os' => $device_data->os ?? null,
            'os_version' => $device_data->os_version ?? null,
            'redirect' => Config::get('medal.authentication.reader_callback_url'),
            'status' => ($device_data->status ?? null) == 'active' ? 1 : 0,
            'health_facility_id' => $health_facility->id ?? null,
            'last_seen' => null,

            'user_id' => $user_id,
        ];

        $device = $device_service->add($device_request);
        $health_facility_service->assignDevice($health_facility, $device);
    }

    private static function getMedicalStaffRoleId($role_name)
    {
        return Cache::store('array')->rememberForever('migration_role_id_' . $role_name, function () use ($role_name) {
            return DB::table('medical_staff_roles')->where('type', '=', $role_name)->first()->id;
        });
    }

    private static function addMedicalStaffToDB($medical_staff_service, $health_facility, $medical_staff_data)
    {
        $medical_staff_request = [
            'id' => $medical_staff_data->id,
            'first_name' => $medical_staff_data->first_name,
            'last_name' => $medical_staff_data->last_name,
            'medical_staff_role_id' => self::getMedicalStaffRoleId($medical_staff_data->role),
            'health_facility_id' => $health_facility->id,
        ];

        $medical_staff_service->add($medical_staff_request);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        # Get admin user id
        $user = DB::table('users')->where('email', '=', 'admin@dynamic.com')->first();
        if ($user == null) {
            $this->error('Could not store data: user admin@dynamic.com does not exist.');
            return;
        }
        $user_id = $user->id;

        $health_facility_service = new HealthFacilityService();
        $device_service = new DeviceService();
        $medical_staff_service = new MedicalStaffService();

        # Reset data tables
        foreach (Device::all() as $device) {
            $device->delete();
            DB::table('oauth_clients')->where('id', '=', $device->oauth_client_id)->delete();
        }

        foreach (MedicalStaff::all() as $medical_staff) {
            $medical_staff_service->remove($medical_staff);
        }

        foreach (HealthFacility::all() as $health_facility) {
            $health_facility->delete();
        }

        # Populate data tables

        $study_id = str_replace(' ', '%20', trim(Config::get('app.study_id')));
        $datas = $this->getMedalCData($study_id);

        foreach ($datas as $data) {
            $health_facility_data = self::getHealthFacilityData($data);
            $devices_data = self::getDevicesData($data);
            $medical_staffs_data = self::getMedicalStaffsData($data);

            # Add new health facility
            $health_facility = self::addHealthFacilityToDB($health_facility_data);

            # Add all devices related to that health facility
            foreach ($devices_data as $device_data) {
                self::addDeviceToDB($health_facility_service, $device_service, $health_facility, $device_data, $user_id);
            }

            # Add all medical staff related to that health facility
            foreach ($medical_staffs_data as $medical_staff_data) {
                self::addMedicalStaffToDB($medical_staff_service, $health_facility, $medical_staff_data);
            }

            #Add hub device for client_server health facilities
            if ($health_facility->hf_mode == 'client_server') {
                $hub_data = new stdClass();
                $hub_data->name = $health_facility->name . ' Hub';
                $hub_data->type = 'hub';
                $hub_data->status = 'active';
                self::addDeviceToDb($health_facility_service, $device_service, $health_facility, $hub_data, $user_id);
            }
        }
        $this->info('Data successfully retrieved.');
    }
}
