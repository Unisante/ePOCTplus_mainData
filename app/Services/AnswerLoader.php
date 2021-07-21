<?php

namespace App\Services;

use App\Answer;
use App\Services\ModelLoader;

class AnswerLoader extends ModelLoader {
    protected $nodeData;
    protected $node;

    /**
     * Constructor
     *
     * @param array $nodeData
     * @param Node $node
     */
    public function __construct($nodeData, $node) {
        parent::__construct($nodeData);
        $this->nodeData = $nodeData;
        $this->node = $node;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'node_id' => $this->node->id
        ]);
    }

    protected function model()
    {
        return Answer::class;
    }

    protected function configName()
    {
        return 'answer';
    }
}