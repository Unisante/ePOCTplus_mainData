<?php

namespace App\Services;

use App\ManagementReference;
use App\Services\ModelLoader;

class ManagementReferenceLoader extends ModelLoader {
    protected $managementRefData;
    protected $diagnosisRef;
    protected $management;

    /**
     * Constructor
     *
     * @param object $data
     * @param DiagnosisReference $diagnosisRef
     * @param Management $management
     * 
     */
    public function __construct($managementRefData, $diagnosisRef, $management) {
        $this->managementRefData = $managementRefData;
        $this->diagnosisRef = $diagnosisRef;
        $this->management = $management;
    }

    protected function getKeys()
    {
        return [
            'diagnosis_id' => $this->diagnosisRef->id,
            'management_id' => $this->management->id
        ];
    }

    protected function model()
    {
        return ManagementReference::class;
    }

    protected function configName()
    {
        return 'management_reference';
    }
}