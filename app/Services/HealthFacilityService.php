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

    public function assignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = $healthFacility->id;
        $device->save();
        return $device;
    }

    public function unassignDevice(HealthFacility $healthFacility,Device $device){
        $device->health_facility_id = null;
        $device->save();
        return $device;
    }

    
}