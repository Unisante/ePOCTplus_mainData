<?php

namespace App\Services;

use App\MedicalCaseAnswer;
use App\Services\ModelLoader;

class AdditionalDrugLoader extends ModelLoader {
    protected $additionalDrugData;

    /**
     * Undocumented function
     *
     * @param object $additionalDrugData
     * @param MedicalCase $medicalCase
     * @param Drug $drug
     * @param Version $version
     */
    public function __construct($additionalDrugData, $medicalCase, $drug, $version) {
        $this->additionalDrugData = $additionalDrugData;
        $this->medicalCase = $medicalCase;
        $this->drug = $drug;
        $this->version = $version;
    }

    public function getKeys()
    {
        return [
            'drug_id' => $this->drug->id,
            'medical_case_id' => $this->medicalCase->id,
            'version_id' => $this->version->id // TODO why do we need the version here??
        ];
    }

    public function getValues()
    {
        return [
            'agreed' => $this->additionalDrugData['agreed'] ?? false,
            'formulationSelected' => $this->additionalDrugData['formulationSelected'] ?? false
        ];
    }

    public function model()
    {
        return MedicalCaseAnswer::class;
    }
}