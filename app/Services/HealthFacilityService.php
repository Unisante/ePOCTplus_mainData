<?php


namespace App\Services;

use App\Device;
use App\HealthFacility;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DeviceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class HealthFacilityService {

    /**
     * Assigns the given $device to the $healthFacility
     */
    public function assignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = $healthFacility->id;
        $device->save();
        return $device;
    }

    /**
     * Unassigns the given $device from the $healthFacility
     */
    public function unassignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = null;
        $device->save();
        return $device;
    }

    
}