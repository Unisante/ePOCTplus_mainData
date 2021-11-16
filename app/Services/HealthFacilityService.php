<?php


namespace App\Services;

use App\Device;
use App\HealthFacility;
use App\MedicalStaff;


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

    public function assignMedicalStaff(HealthFacility $health_facility, MedicalStaff $medical_staff){
        $medical_staff->health_facility_id = $health_facility->id;
        $medical_staff->save();
        return $medical_staff;
    }

    public function unassignMedicalStaff(HealthFacility $health_facility, MedicalStaff $medical_staff){
        $medical_staff->health_facility_id = null;
        $medical_staff->save();
        return $medical_staff;
    }

    
}