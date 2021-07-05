<?php

namespace App\Services;

use App\Drug;
use App\Services\ModelLoader;

class DrugLoader extends ModelLoader {
    protected $drugData;
    protected $diagnosis;

    /**
     * Undocumented function
     *
     * @param object $drugData
     * @param Diagnosis $diagnosis
     */
    public function __construct($drugData, $diagnosis) {
        $this->drugData = $drugData;
        $this->diagnosis = $diagnosis;
    }

    public function getKeys()
    {
        return [
            //'diagnosis_id' => $this->diagnosis->id,
            'medal_c_id' => $this->drugData['id']
        ];
    }

    public function getValues()
    {
        return [
            'type' => $this->drugData['type'],
            'label' => $this->drugData['label'][env('LANGUAGE')],
            'description' => $this->drugData['description'][env('LANGUAGE')] ?? '',
            'is_antibiotic' => $this->drugData['is_antibiotic'] ?? null,
            'is_anti_malarial' => $this->drugData['is_anti_malarial'] ?? null
        ];
    }

    public function model()
    {
        return Drug::class;
    }
}