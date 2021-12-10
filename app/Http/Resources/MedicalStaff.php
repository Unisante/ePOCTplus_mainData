<?php

namespace App\Http\Resources;

use App\HealthFacility;
use App\MedicalStaffRole;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class MedicalStaff extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Retrieve all roles and cache the result.
        $medical_staff_roles = Cache::store('array')->rememberForever('medical_staff_roles', function () {
            return MedicalStaffRole::all();
        });
        $roles_label = [];
        $medical_staff_roles->each(function($role) use (&$roles_label){
            $roles_label[$role->id] = $role->label;
        });
        
        // Retrieve all health facilities and cache result.
        $health_facilities = Cache::store('array')->rememberForever('health_facilities_db', function () {
            return HealthFacility::all();
        });
        $health_facilities_label = [];
        $health_facilities->each(function($hf) use (&$health_facilities_label){
            $health_facilities_label[$hf->id] = $hf->name;
        });

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'medical_staff_role_id' => $this->medical_staff_role_id,
            'role' => $roles_label[$this->medical_staff_role_id] ?? '-',
            'health_facility' => $health_facilities_label[$this->health_facility_id] ?? '-'
        ];
    }
}
