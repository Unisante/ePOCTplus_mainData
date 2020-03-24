<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
}
