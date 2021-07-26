<?php

namespace App\Http\Resources;

use App\DeviceType;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $hfName = null;
        if ($this->healthFacility != null){
            $hfName = $this->healthFacility->name;
        }
        $deviceType = DeviceType::where('type',$this->type)->first();
        $typeLabel = $deviceType->label;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type,
            "type_label" => $typeLabel,
            "model" => $this->model,
            "brand" => $this->brand,
            "os" => $this->os,
            "os_version" => $this->os_version,
            "health_facility_id" => $this->health_facility_id,
            "health_facility_name" => $hfName,
            "oauth_client_id" => $this->oauth_client_id,
            "oauth_client_secret" => $this->oauth_client_secret,
            "mac_address" => $this->mac_address,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
