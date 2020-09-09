<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Algorithm extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($algorithm_id, $name) {
    $algorithm = Algorithm::where('medal_c_id', $algorithm_id)
    ->updateOrCreate(
      [
        'name' => $name
      ]
    );
    return $algorithm;
  }
}
