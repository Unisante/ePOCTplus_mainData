<?php

namespace App\Services;

use App\MedicalCase;
use App\Services\ModelLoader;
use DateTime;

class MedicalCaseLoader extends ModelLoader {
    protected $caseData;
    protected $patient;

    /**
     * Undocumented function
     *
     * @param object $caseData
     * @param Patient $patient
     */
    public function __construct($caseData, $patient) {
        $this->caseData = $caseData;
        $this->patient = $patient;
    }

    public function getKeys()
    {
        return [
            'local_medical_case_id' => $this->caseData['id']
        ];
    }

    public function getValues()
    {
        return [
            'patient_id'=> $this->patient->id,
            'version_id'=> $this->caseData['version_id'],
            'created_at'=> new DateTime($this->caseData['created_at']),
            'updated_at'=> new DateTime($this->caseData['updated_at']),
            'isEligible'=> $this->caseData['isEligible'],
            'consent'=> $this->caseData['consent'],
            'group_id'=> $this->patient->group_id, // TODO this seems redundant
        ];
    }

    public function model()
    {
        return MedicalCase::class;
    }
}