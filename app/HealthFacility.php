<?php

namespace App;

use App\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class HealthFacility extends Model
{
    protected $fillable = [
        "name",
        "user_id",
        "local_data_ip",
        "pin_code",
        "lat",
        "long",
        "country",
        "area",
        "group_id",
        "hf_mode",
    ];
    protected $guarded = [];

    // fetch the the facility information
    public static function fetchHealthFacility($group_id = null)
    {
        if ($group_id != null) {
            $facility_doesnt_exist = HealthFacility::where('group_id', $group_id)->doesntExist();
            if ($facility_doesnt_exist) {
                // setting headers for when we secure this part of quering from medal c
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                //   'Header-Key: Header-Value',
                //   'Header-Key-2: Header-Value-2'
                // ));
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => Config::get('medal-data.urls.creator_health_facility_url') . $group_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Cache-Control: no-cache",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    return response()->json([
                        "error" => json_decode($err, true),
                    ]);
                } else {
                    $health_facilty = json_decode($response, true);
                    if (array_key_exists('id', $health_facilty)) {
                        self::store($health_facilty);
                    }
                }
            }
        }
    }
    public static function store($healthFacilityInfo)
    {
        if ($healthFacilityInfo['id'] != null && $healthFacilityInfo['name'] && $healthFacilityInfo['longitude'] != null && $healthFacilityInfo['latitude'] != null && $healthFacilityInfo['architecture'] != null) {
            $facility = HealthFacility::firstOrCreate(
                [
                    'facility_name' => $healthFacilityInfo['name'],
                ],
                [
                    'group_id' => $healthFacilityInfo['id'],
                    'long' => $healthFacilityInfo['longitude'],
                    'lat' => $healthFacilityInfo['latitude'],
                    'hf_mode' => $healthFacilityInfo['architecture'],
                ]
            );
        }
    }

    public function medical_cases()
    {
        return $this->hasMany('App\MedicalCase', 'group_id', 'group_id');
    }

    public function log_cases()
    {
        return $this->hasMany('App\JsonLog', 'group_id', 'group_id');
    }

    public function patients()
    {
        return $this->hasMany('App\Patient', 'group_id', 'group_id');
    }

    public function versionJson()
    {
        return $this->hasOne('App\VersionJson');
    }

    public function healthFacilityAccess()
    {
        return $this->hasOne('App\HealthFacilityAccess')->where("access", true);
    }

    public function medical_staff()
    {
        return $this->hasMany(MedicalStaff::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function patients_medical_cases()
    {
        return $this->hasManyThrough(MedicalCase::class, Patient::class, 'group_id', 'patient_id', 'group_id', 'id');
    }
}
