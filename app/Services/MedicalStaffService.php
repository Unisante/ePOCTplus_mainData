<?php

namespace App\Services;

use App\MedicalStaff;
use Illuminate\Support\Facades\Log;

class MedicalStaffService {
    // TODO oauth client
    public function add($validated_request): MedicalStaff{
        $medical_staff = new MedicalStaff($validated_request);
        Log::debug($validated_request);
        $medical_staff->save();
        return $medical_staff;
    }

    // TODO oauth client
    public function update($validated_request, MedicalStaff $medical_staff): MedicalStaff{
        $medical_staff->fill($validated_request)->save();
        return $medical_staff;
    }


    // TODO oauth client
    public function remove(MedicalStaff $medical_staff){
        $id = $medical_staff->id;
        $medical_staff->delete();
        return $id;
    }
}