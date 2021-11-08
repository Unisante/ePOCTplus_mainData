<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalStaff extends Model
{
    protected $fillable = [
        "first_name",
        "last_name",
    ];
    protected $guarded = [];

    public function healthFacility(){
        return $this->belongsTo('App\HealthFacility');
    }

    public function medicalStaffRole(){
        return $this->belongsTo('App/MedicalStaffRole');
    }

}
