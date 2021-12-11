<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

class MedicalStaffsAPI extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->medicalStaffRole->type,
            'health_facility_id' => $this->health_facility_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'algo_language' => Config::get('medal.creator.language'),
            'app_language' => Config::get('medal.creator.language')
        ];
    }
}
