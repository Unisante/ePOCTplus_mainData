<?php

namespace App\Services;

use App\ManagementReference;
use App\Services\ModelLoader;

class ManagementReferenceLoader extends ModelLoader {
    protected $managementRefData;
    protected $diagnosisRef;
    protected $management;

    /**
     * Undocumented function
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

    public function getKeys()
    {
        return [
            'diagnosis_id' => $this->diagnosisRef->id,
            'management_id' => $this->management->id
        ];
    }

    public function getValues()
    {
        return [
            'agreed' => $this->diagnosisRef->agreed, // TODO this does not make sense
        ];
    }

    public function model()
    {
        return ManagementReference::class;
    }
}