<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Algorithm extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($algorithm_id, $name) {
    $algorithm = Algorithm::firstWhere('medal_c_id', $algorithm_id);
    if ($algorithm->isEmpty()) {
      $algorithm = Algorithm::create(['name' => $name, 'medal_c_id' => $algorithm_id]);
    } else {
      $algorithm->update(['name' => $name]);
    }
    return $algorithm;
  }
}
