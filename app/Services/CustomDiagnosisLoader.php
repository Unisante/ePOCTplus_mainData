<?php

namespace App\Services;

use App\Custom_diagnosis;
use App\Services\ModelLoader;

class CustomDiagnosisLoader extends ModelLoader {
    protected $customDiagnosisData;
    protected $medicalCase;

    /**
     * Undocumented function
     *
     * @param object $data
     * @param MedicalCase $medicalCase
     * @param Drug $drug
     * 
     */
    public function __construct($customDiagnosisData, $medicalCase) {
        $this->customDiagnosisData = $customDiagnosisData;
        $this->medicalCase = $medicalCase;
    }

    public function getKeys()
    {
        return [
            'medical_case_id' => $this->medicalCase->id,
            'label' => $this->customDiagnosisData['label']
        ];
    }

    public function getValues()
    {
        return [
            'drugs' => implode(',', $this->customDiagnosisData['drugs'])
        ];
    }

    public function model()
    {
        return Custom_diagnosis::class;
    }
}