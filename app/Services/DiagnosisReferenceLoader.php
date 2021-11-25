<?php

namespace App\Services;

use App\DiagnosisReference;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Log;

class DiagnosisReferenceLoader extends ModelLoader {
    protected $diagnosisRefData;
    protected $medicalCase;
    protected $diagnosis;
    protected $additional;
    protected $isExcluded;
    protected $isAgreed;

    /**
     * Constructor
     *
     * @param array $data
     * @param MedicalCase $medicalCase
     * @param Diagnosis $diagnosis
     * @param bool $additional
     * @param bool $isExcluded
     * @param bool $isAgreed
     */
    public function __construct($diagnosisRefData, $medicalCase, $diagnosis, $additional, $isExcluded, $isAgreed) {
        parent::__construct($diagnosisRefData);
        $this->diagnosisRefData = $diagnosisRefData;
        $this->medicalCase = $medicalCase;
        $this->diagnosis = $diagnosis;
        $this->additional = $additional;
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
            'additional' => $this->additional
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
