<?php

namespace App\Services;

use App\AnswerType;
use App\Services\ModelLoader;

class AnswerTypeLoader extends ModelLoader {
    protected $nodeData;

    /**
     * Constructor
     *
     * @param array $nodeData
     * @param Algorithm $algorithm
     */
    public function __construct($nodeData) {
        parent::__construct($nodeData);
        $this->nodeData = $nodeData;
    }

    protected function model()
    {
        return AnswerType::class;
    }

    protected function configName()
    {
        return 'answer_type';
    }
}