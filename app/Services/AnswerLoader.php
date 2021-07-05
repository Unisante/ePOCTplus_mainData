<?php

namespace App\Services;

use App\Answer;
use App\Services\ModelLoader;

class AnswerLoader extends ModelLoader {
    protected $nodeData;
    protected $node;

    /**
     * Undocumented function
     *
     * @param object $nodeData
     * @param Node $node
     */
    public function __construct($nodeData, $node) {
        $this->nodeData = $nodeData;
        $this->node = $node;
    }

    public function getKeys()
    {
        return [
            'medal_c_id' => $this->nodeData['id'],
            'node_id' => $this->node->id
        ];
    }

    public function getValues()
    {
        return [
            'label' => $this->nodeData['label'][env('LANGUAGE')]
        ];
    }

    public function model()
    {
        return Answer::class;
    }
}