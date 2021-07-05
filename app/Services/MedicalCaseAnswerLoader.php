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
     * Undocumented function
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

    public function getKeys()
    {
        return [
            'medical_case_id' => $this->medicalCase->id,
            'answer_id' => $this->answer->id ?? null,
            'node_id' => $this->node->id ?? null,
        ];
    }

    public function getValues()
    {
        return [
            'value' => $this->answerData['value']['label'][env('LANGUAGE')] ?? null
        ];
    }

    public function model()
    {
        return MedicalCaseAnswer::class;
    }
}