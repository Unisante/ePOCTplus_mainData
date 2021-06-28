<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        "name",
        "mac_address",
        "model",
        "brand",
        "os",
        "os_version",
        "status",
    ];
    protected $guarded = [];

}
