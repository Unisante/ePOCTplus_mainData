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
     * @param object $nodeData
     * @param Algorithm $algorithm
     * @param AnswerType  $answerType
     */
    public function __construct($nodeData, $algorithm, $answerType) {
        $this->nodeData = $nodeData;
        $this->algorithm = $algorithm;
        $this->answerType = $answerType;
    }

    public function getKeys()
    {
        return [
            'medal_c_id' => $this->nodeData['id']
        ];
    }

    public function getValues()
    {
        return [
            'reference' => $this->nodeData['reference'] ?? 0,
            'label' => $this->nodeData['label'][env('LANGUAGE')],
            'type' => $this->nodeData['type'],
            'category' => $this->nodeData['category'],
            'priority' => $this->nodeData['priority'] ?? 0,
            'stage' => $this->nodeData['reference'] ?? '',
            'description' => $this->nodeData['label'][env('LANGUAGE')] ?? '',
            'formula' => $this->nodeData['formula'] ?? '',
            'answer_type_id' => $this->answerType->id,
            'algorithm_id'=> $this->algorithm->id,
            'is_identifiable'=> $this->nodeData['is_identifiable']
        ];
    }

    public function model()
    {
        return Node::class;
    }
}