<?php

namespace App\Services;

use App\CustomDrug;
use App\Services\ModelLoader;

class CustomDrugLoader extends ModelLoader {
    protected $customDrugData;
    protected $customDiagnosis;
    /**
     * Undocumented function
     *
     * @param object $data
     * @param Custom_diagnosis $customDiagnosis
     * 
     */
    public function __construct($customDrugData, $customDiagnosis) {
        parent::__construct($customDrugData);
        $this->customDrugData = $customDrugData;
        $this->customDiagnosis = $customDiagnosis;    
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'custom_diagnosis_id' => $this->customDiagnosis->id,
        ]);
    }

    protected function model()
    {
        return CustomDrug::class;
    }

    protected function configName()
    {
        return "custom_drug";
    }
}