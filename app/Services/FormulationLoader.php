<?php

namespace App\Services;

use App\Formulation;
use App\Services\ModelLoader;

class FormulationLoader extends ModelLoader {
    protected $formulationData;
    protected $drug;

    /**
     * Undocumented function
     *
     * @param object $formulationData
     * @param Drug $drug
     */
    public function __construct($formulationData, $drug) {
        $this->formulationData = $formulationData;
        $this->drug = $drug;
    }

    public function getKeys()
    {
        return [
            'drug_id' => $this->drug->id,
            // TODO formulations should have a medal_c_id column for consistency
            //'medal_c_id' => $this->formulationData['id']
        ];
    }

    public function getValues()
    {
        return [
            'medication_form' => $this->formulationData['medication_form'],
            'administration_route_category' => $this->formulationData['administration_route_category'],
            'administration_route_name' => $this->formulationData['administration_route_name'],
            'liquid_concentration' => $this->formulationData['liquid_concentration'],
            'dose_form' => $this->formulationData['dose_form'],
            'unique_dose' => $this->formulationData['unique_dose'],
            'by_age' => $this->formulationData['by_age'],
            'minimal_dose_per_kg' => $this->formulationData['minimal_dose_per_kg'],
            'maximal_dose_per_kg' => $this->formulationData['maximal_dose_per_kg'],
            'maximal_dose' => $this->formulationData['maximal_dose'],
            'doses_per_day' => $this->formulationData['doses_per_day'],
            'description' => $this->formulationData['description'][env('LANGUAGE')] ?? ''
        ];
    }

    public function model()
    {
        return Formulation::class;
    }
}