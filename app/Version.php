<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Version extends Model implements Auditable
{
  use \OwenIt\Auditing\Auditable;
  protected $guarded = [];

  public static function getOrCreate($name, $algorithm_id,$version_id) {
    $version = Version::firstOrCreate(
      [
        'name' => $name, 'algorithm_id' => $algorithm_id
      ],
      [
        'medal_c_id' => $version_id
      ]
    );
    return $version;
  }
}
