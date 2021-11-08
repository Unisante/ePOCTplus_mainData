<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalStaffRole extends Model
{
    protected $fillable = [
        "label",
        "alias",
    ];
    protected $guarded = [];
}
