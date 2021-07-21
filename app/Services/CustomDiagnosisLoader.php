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
        parent::__construct($customDiagnosisData);
        $this->customDiagnosisData = $customDiagnosisData;
        $this->medicalCase = $medicalCase;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'medical_case_id' => $this->medicalCase->id,
        ]);
    }

    protected function getValues()
    {
        return [
            'drugs' => '',
        ];
    }

    protected function model()
    {
        return Custom_diagnosis::class;
    }

    protected function configName()
    {
        return "custom_diagnosis";
    }
}