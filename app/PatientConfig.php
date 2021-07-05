<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Version;
class PatientConfig extends Model
{
  protected $table = 'patient_configs';
  protected $guarded = [];
  protected $casts = [
    'config' => 'array',
];
}
