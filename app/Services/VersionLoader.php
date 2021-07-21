<?php

namespace App\Services;

use App\Algorithm;
use App\Services\ModelLoader;
use App\Version;

class VersionLoader extends ModelLoader {
    protected $data;
    protected $algorithm;

    /**
     * Constructor
     *
     * @param array $data
     * @param Algorithm $algorithm
     */
    public function __construct($data, $algorithm) {
        parent::__construct($data);
        $this->data = $data;
        $this->algorithm = $algorithm;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'algorithm_id' => $this->algorithm->id
        ]);
    }

    protected function model()
    {
        return Version::class;
    }

    protected function configName()
    {
        return 'version';
    }
}