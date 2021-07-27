<?php

namespace App\Services;

use App\CustomDiagnosis;
use App\Services\ModelLoader;

class CustomDiagnosisLoader extends ModelLoader {
    protected $customDiagnosisData;
    protected $medicalCase;

    /**
     * Constructor
     *
     * @param array $data
     * @param MedicalCase $medicalCase
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

    protected function model()
    {
        return CustomDiagnosis::class;
    }

    protected function configName()
    {
        return "custom_diagnosis";
    }
}