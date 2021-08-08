<?php

namespace App\Services;

use App\MedicalCase;
use App\Services\ModelLoader;

class MedicalCaseLoader extends ModelLoader {
    protected $caseData;
    protected $patient;

    /**
     * Constructor
     *
     * @param object $caseData
     * @param Patient $patient
     * @param Version $version
     */
    public function __construct($caseData, $patient, $version) {
        parent::__construct($caseData);
        $this->caseData = $caseData;
        $this->patient = $patient;
        $this->version = $version;
    }

    protected function getValues()
    {
        return array_merge(parent::getValues(), [
            'patient_id' => $this->patient->id,
            'version_id' => $this->version->id,
        ]);
    }

    protected function model()
    {
        return MedicalCase::class;
    }

    protected function configName()
    {
        return 'medical_case';
    }
}