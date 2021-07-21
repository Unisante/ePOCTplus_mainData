<?php

namespace App\Services;

use App\DiagnosisReference;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Log;

class DiagnosisReferenceLoader extends ModelLoader {
    protected $diagnosisRefData;
    protected $medicalCase;
    protected $diagnosis;
    protected $isProposed;
    protected $isExcluded;
    protected $isAgreed;

    /**
     * Constructor
     *
     * @param array $data
     * @param MedicalCase $medicalCase
     * @param Diagnosis $diagnosis
     * @param bool $isProposed
     * @param bool $isExcluded
     * @param bool $isAgreed
     */
    public function __construct($diagnosisRefData, $medicalCase, $diagnosis, $isProposed, $isExcluded, $isAgreed) {
        parent::__construct($diagnosisRefData);
        $this->diagnosisRefData = $diagnosisRefData;
        $this->medicalCase = $medicalCase;
        $this->diagnosis = $diagnosis;
        $this->isProposed = $isProposed;
        $this->isExcluded = $isExcluded;
        $this->isAgreed = $isAgreed;
    }

    protected function getKeys()
    {
        return [
            'medical_case_id' => $this->medicalCase->id,
            'diagnosis_id' => $this->diagnosis->id,
        ];
    }

    protected function getValues()
    {
        return [
            'agreed' => $this->isAgreed,
            'excluded' => $this->isExcluded,
            'proposed_additional' => $this->isProposed
        ];
    }

    protected function model()
    {
        return DiagnosisReference::class;
    }

    protected function configName()
    {
        return 'diagnosis_reference';
    }
}