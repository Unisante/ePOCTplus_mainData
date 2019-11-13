<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $guarded = [];

    public static function get_or_create($name, $algorithm_id) {
        $versions = Version::where(['name' => $name, 'algorithm_id' => $algorithm_id])->get();
        if ($versions->isEmpty()) {
            // TODO Use a real medal_c_id
            $version = Version::create(['name' => $name, 'algorithm_id' => $algorithm_id, 'medal_c_id' => 1]);
        } else {
            $version = $versions->first();
        }

        return $version;
    }
}
