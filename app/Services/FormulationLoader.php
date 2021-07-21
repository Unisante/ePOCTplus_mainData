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
        parent::__construct($formulationData);
        $this->formulationData = $formulationData;
        $this->drug = $drug;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'drug_id' => $this->drug->id,
        ]);
    }

    protected function model()
    {
        return Formulation::class;
    }

    protected function configName()
    {
        return 'formulation';
    }
}