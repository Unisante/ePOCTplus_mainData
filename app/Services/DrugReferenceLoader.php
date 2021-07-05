<?php

namespace App\Services;

use App\DrugReference;
use App\Services\ModelLoader;

class DrugReferenceLoader extends ModelLoader {
    protected $drugRefData;
    protected $diagnosisRef;
    protected $drug;
    /**
     * Undocumented function
     *
     * @param object $data
     * @param DiagnosisReference $diagnosisRef
     * @param Drug $drug
     * 
     */
    public function __construct($drugRefData, $diagnosisRef, $drug) {
        $this->drugRefData = $drugRefData;
        $this->diagnosisRef = $diagnosisRef;
        $this->drug = $drug;
    }

    public function getKeys()
    {
        return [
            'diagnosis_id' => $this->diagnosisRef->id,
            'drug_id' => $this->drug->id
        ];
    }

    public function getValues()
    {
        return [
            'agreed' => $this->drugRefData['agreed'] ?? false,
            'formulationSelected' => 0 // TODO wtf
        ];
    }

    public function model()
    {
        return DrugReference::class;
    }
}