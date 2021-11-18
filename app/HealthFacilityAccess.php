<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthFacilityAccess extends Model
{
    protected $attributes = [
        'access' => true,
    ];

    protected $fillable = [
        "medal_r_json_version",
        "creator_version_id",
        "medal_c_algorithm_id",
    ];
}
