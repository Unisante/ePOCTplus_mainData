<?php

namespace App\Services;

use App\DiagnosisReference;
use App\Services\ModelLoader;

class DiagnosisReferenceLoader extends ModelLoader {
    protected $diagnosisRefData;
    protected $medicalCase;
    protected $diagnosis;
    protected $isProposed;

    /**
     * Undocumented function
     *
     * @param object $data
     * @param MedicalCase $medicalCase
     * @param Diagnosis $diagnosis
     * @param bool isProposed
     */
    public function __construct($diagnosisRefData, $medicalCase, $diagnosis, $isProposed) {
        $this->diagnosisRefData = $diagnosisRefData;
        $this->medicalCase = $medicalCase;
        $this->diagnosis = $diagnosis;
        $this->isProposed = $isProposed;
    }

    public function getKeys()
    {
        return [
            'medical_case_id' => $this->medicalCase->id,
            'diagnosis_id' => $this->diagnosis->id,
        ];
    }

    public function getValues()
    {
        return [
            'agreed' => $this->diagnosisRefData['agreed'] ?? true,
            'proposed_additional' => $this->isProposed
        ];
    }

    public function model()
    {
        return DiagnosisReference::class;
    }
}