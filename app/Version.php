<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Version extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  /**
  * making a relationship to medicalCase
  * @return Many medical cases
  */
  public function configurations()
  {
    return $this->hasOne('App\PatientConfig');
  }

  /**
   * making a relationship to algorithm
   * @return one to one medical cases
   */
  public function algorithm()
  {
    return $this->hasOne('App\Algorithm', 'id', 'algorithm_id');
  }
}
