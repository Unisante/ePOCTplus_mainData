<?php

namespace App\Services;

use App\Management;
use App\Services\ModelLoader;

class ManagementLoader extends ModelLoader {
    protected $managementData;
    protected $diagnosis;

    /**
     * Undocumented function
     *
     * @param object $managementData
     * @param Diagnosis $diagnosis
     */
    public function __construct($managementData, $diagnosis) {
        $this->managementData = $managementData;
        $this->diagnosis = $diagnosis;
    }

    public function getKeys()
    {
        return [
            'diagnosis_id'=>$this->diagnosis->id,
            'medal_c_id'=>$this->managementData['id']
        ];
    }

    public function getValues()
    {
        return [
            'type' => $this->managementData['type'],
            'label' => $this->managementData['label'][env('LANGUAGE')],
            'description' => $this->managementData['description'][env('LANGUAGE')] ?? ''
        ];
    }

    public function model()
    {
        return Management::class;
    }
}