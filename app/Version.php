<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Version extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];
  public static function store($name,$medal_c_id,$algorithm_id){
      $version = new Version;
      $version->name = $name;
      $version->medal_c_id = $medal_c_id;
      $version->algorithm_id = $algorithm_id;
      $version->save();
      return $version;
  }
}
