<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Management;
use App\Diagnosis;
class ManagementReference extends Model
{
  protected $guarded = [];

  /**
   * Make diagnoses relation
   * @return one to one drub Diagnosis
   */
  public function managements() {
    return $this->hasOne('App\Management','id','management_id');
  }
}
