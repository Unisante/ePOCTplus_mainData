<?php

namespace App\Http\Resources;

use App\DeviceType;
use App\HealthFacility;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class Device extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Retrieve all health facilities and cache result.
        $health_facilities = Cache::store('array')->rememberForever('health_facilities_db', function () {
            return HealthFacility::all();
        });
        $health_facilities_label = [];
        $health_facilities->each(function($hf) use (&$health_facilities_label){
            $health_facilities_label[$hf->id] = $hf->name;
        });
        
        $device_type = Cache::store('array')->rememberForever('device_type_' . $this->type, function () {
            return DeviceType::where('type', $this->type)->first();
        });
        $type_label = $device_type->label;
        
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type,
            "type_label" => $type_label,
            "model" => $this->model,
            "brand" => $this->brand,
            "os" => $this->os,
            "os_version" => $this->os_version,
            "oauth_client_id" => $this->oauth_client_id,
            "mac_address" => $this->mac_address,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "redirect" => $this->redirect,
            "last_seen" => $this->last_seen,
            'health_facility_name' => $health_facilities_label[$this->health_facility_id] ?? '-'
        ];
    }
}
