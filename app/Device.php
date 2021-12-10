<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        "name",
        'type',
        "mac_address",
        "model",
        "brand",
        "os",
        "os_version",
        "status",
        "redirect",
        "oauth_client_id",
    ];
    protected $guarded = [];

    public function health_facility(){
        return $this->belongsTo('App\HealthFacility');
    }

}
