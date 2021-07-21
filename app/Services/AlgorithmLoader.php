<?php

namespace App\Services;

use App\Algorithm;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Config;

class AlgorithmLoader extends ModelLoader {
    protected $data;

    /**
     * Undocumented function
     *
     * @param object $data
     */
    public function __construct($data) {
        parent::__construct($data);
        $this->data = $data;
    }

    protected function model()
    {
        return Algorithm::class;
    }

    protected function configName()
    {
        return 'algorithm';
    } 
}