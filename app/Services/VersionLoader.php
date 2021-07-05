<?php

namespace App\Services;

use App\Algorithm;
use App\Services\ModelLoader;
use App\Version;
use Illuminate\Support\Facades\Log;

class VersionLoader extends ModelLoader {
    protected $data;
    protected $algorithm;

    /**
     * Undocumented function
     *
     * @param object $data
     * @param Algorithm $algorithm
     */
    public function __construct($data, $algorithm) {
        // TODO would probably make more sense to pass only the algorithm's id
        $this->data = $data;
        $this->algorithm = $algorithm;
    }

    public function getKeys()
    {
        return [
            'name' => $this->data['version_name'],
            'medal_c_id' => $this->data['version_id'],
            'algorithm_id' => $this->algorithm->id,
        ];
    }

    public function getValues()
    {
        return [];
    }

    public function model()
    {
        return Version::class;
    }
}