<?php

namespace App\Services;

use App\Algorithm;
use App\Node;
use App\Services\ModelLoader;

class NodeLoader extends ModelLoader {
    protected $nodeData;
    protected $algorithm;
    protected $answerType;

    /**
     * Constructor
     *
     * @param array $nodeData
     * @param Algorithm $algorithm
     * @param AnswerType  $answerType
     */
    public function __construct($nodeData, $algorithm, $answerType) {
        parent::__construct($nodeData);

        $this->nodeData = $nodeData;
        $this->algorithm = $algorithm;
        $this->answerType = $answerType;
    }

    protected function getValues()
    {
        return array_merge(parent::getValues(), [
            'answer_type_id' => $this->answerType->id,
            'algorithm_id'=> $this->algorithm->id,
        ]);
    }

    protected function model()
    {
        return Node::class;
    }

    protected function configName()
    {
        return 'node';
    }
}