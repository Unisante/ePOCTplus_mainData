<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Algorithm extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($algorithm_id, $name) {
    $algorithm_exist=Algorithm::where('medal_c_id',$algorithm_id)->exists();
    $algorithm =(object) Algorithm::when($algorithm_exist, function ($query,$algorithm_id) {
                    return $query->where('medal_c_id',$algorithm_id);
                })
                ->first();
    if(! $algorithm_exist ){
      $algorithm=Algorithm::create([
        "medal_c_id"=>$algorithm_id,
        "name"=>$name
      ]);
    }
    return $algorithm;
  }
}
