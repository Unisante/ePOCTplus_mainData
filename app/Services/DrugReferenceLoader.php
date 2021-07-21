<?php

namespace App\Services;

use App\DrugReference;
use App\Services\ModelLoader;

class DrugReferenceLoader extends ModelLoader {
    protected $drugRefData;
    protected $diagnosisRef;
    protected $drug;
    protected $formulation;
    protected $agreed;
    protected $additional;

    /**
     * Constructor
     *
     * @param object $data
     * @param DiagnosisReference $diagnosisRef
     * @param Drug $drug
     * @param Formulation $formulation
     * @param bool agreed
     * @param bool additional
     */
    public function __construct($drugRefData, $diagnosisRef, $drug, $formulation, $agreed, $additional) {
        parent::__construct($drugRefData);
        $this->drugRefData = $drugRefData;
        $this->diagnosisRef = $diagnosisRef;
        $this->drug = $drug;
        $this->formulation = $formulation;
        $this->agreed = $agreed;
        $this->additional = $additional;
    }

    protected function getKeys()
    {
        return [
            'diagnosis_id' => $this->diagnosisRef->id,
            'drug_id' => $this->drug->id,
            'formulation_id' => $this->formulation->id ?? null,
        ];
    }

    protected function getValues()
    {
        return array_merge(parent::getValues(), [
            'formulationSelected' => 0, // TODO wtf
            'agreed' => $this->agreed,
            'additional' => $this->additional,
        ]);
    }

    protected function model()
    {
        return DrugReference::class;
    }

    protected function configName()
    {
        return 'drug_reference';
    }
}