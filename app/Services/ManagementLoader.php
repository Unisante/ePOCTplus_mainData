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
        parent::__construct($managementData);
        $this->managementData = $managementData;
        $this->diagnosis = $diagnosis;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'diagnosis_id'=>$this->diagnosis->id,
        ]);
    }

    protected function model()
    {
        return Management::class;
    }

    protected function configName()
    {
        return 'management';
    }
}