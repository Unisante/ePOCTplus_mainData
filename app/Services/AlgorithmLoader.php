<?php

namespace App\Services;

use App\Algorithm;
use App\Services\ModelLoader;

class AlgorithmLoader extends ModelLoader {
    protected $data;

    /**
     * Undocumented function
     *
     * @param object $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

    public function getKeys()
    {
        return [
            'name' => $this->data['algorithm_name'],
            'medal_c_id' => $this->data['algorithm_id']
        ];
    }

    public function getValues()
    {
        return [];
    }

    public function model()
    {
        return Algorithm::class;
    }
}