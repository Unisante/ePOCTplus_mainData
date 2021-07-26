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
    ];
    protected $guarded = [];

    public function healthFacility(){
        return $this->belongsTo('App\HealthFacility');
    }

}
