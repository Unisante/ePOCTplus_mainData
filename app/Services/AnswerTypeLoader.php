<?php

namespace App\Services;

use App\AnswerType;
use App\Services\ModelLoader;

class AnswerTypeLoader extends ModelLoader {
    protected $nodeData;

    /**
     * Undocumented function
     *
     * @param object $nodeData
     * @param Algorithm $algorithm
     */
    public function __construct($nodeData) {
        $this->nodeData = $nodeData;
    }

    public function getKeys()
    {
        return [
            'value' => $this->nodeData['value_format']
        ];
    }

    public function getValues()
    {
        return [];
    }

    public function model()
    {
        return AnswerType::class;
    }
}