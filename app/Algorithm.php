<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    protected $guarded = [];

    public static function get_or_create($algorithm_id, $name) {
        $algorithms = Algorithm::where('medal_c_id', $algorithm_id)->get();
        if ($algorithms->isEmpty()) {
            $algorithm = Algorithm::create(['name' => $name, 'medal_c_id' => $algorithm_id]);
        } else {
            $algorithm = $algorithms->first();
            $algorithm->update(['name' => $name]);
        }

        return $algorithm;
    }
}
