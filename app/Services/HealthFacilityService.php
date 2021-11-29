<?php

namespace App\Services;

use App\Device;
use App\HealthFacility;

class HealthFacilityService
{

    /**
     * Assigns the given $device to the $healthFacility
     */
    public function assignDevice(HealthFacility $healthFacility, Device $device)
    {
        $device->health_facility_id = $healthFacility->id;
        $device->save();
        return $device;
    }

    /**
     * Unassigns the given $device from the $healthFacility
     */
    public function unassignDevice(HealthFacility $healthFacility, Device $device)
    {
        $device->health_facility_id = null;
        $device->save();
        return $device;
    }

}
