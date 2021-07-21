<?php

namespace App\Services;

use App\Algorithm;
use App\Node;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Log;

class NodeLoader extends ModelLoader {
    protected $nodeData;
    protected $algorithm;
    protected $answerType;

    /**
     * Undocumented function
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
            'reference'=>0, // TODO
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

    public function __toString()
    {
        return 'node ' . $this->valueFromConfig('keys', 'medal_c_id');
    }
}