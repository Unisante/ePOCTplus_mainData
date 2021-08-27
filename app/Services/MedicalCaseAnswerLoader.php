<?php

namespace App\Services;

use App\MedicalCaseAnswer;
use App\Services\ModelLoader;

class MedicalCaseAnswerLoader extends ModelLoader {
    protected $answerData;
    protected $medicalCase;
    protected $node;
    protected $answer;

    /**
     * Constructor
     *
     * @param object $answerData
     * @param MedicalCase $medicalCase
     * @param Node $node
     * @param Answer $answer
     */
    public function __construct($answerData, $medicalCase, $node, $answer) {
        $this->answerData = $answerData;
        $this->medicalCase = $medicalCase;
        $this->node = $node;
        $this->answer = $answer;
    }

    protected function getKeys()
    {
        return [
            'medical_case_id' => $this->medicalCase->id,
            'answer_id' => $this->answer->id ?? null,
            'node_id' => $this->node->id ?? null,
        ];
    }

    protected function getValues()
    {
        // TODO is this actually used??
        return [
            // 'value' => $this->answerData['value']['label'][env('LANGUAGE')] ?? null
            'value' => $this->answerData['value']
        ];
    }

    protected function model()
    {
        return MedicalCaseAnswer::class;
    }

    protected function configName()
    {
        return 'medal_case_answer';
    }
}
