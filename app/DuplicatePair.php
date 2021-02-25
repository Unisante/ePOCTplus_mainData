<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DuplicatePair extends Model
{
  protected $casts = [
    'pairs' => 'array'
  ];
  protected $guarded = [];

}
