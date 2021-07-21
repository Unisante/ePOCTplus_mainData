<?php

namespace App\Services;

use App\Drug;
use App\Services\ModelLoader;

class DrugLoader extends ModelLoader {
    protected $drugData;
    protected $diagnosis;

    /**
     * Undocumented function
     *
     * @param object $drugData
     * @param Diagnosis $diagnosis
     */
    public function __construct($drugData, $diagnosis) {
        parent::__construct($drugData);
        $this->drugData = $drugData;
        $this->diagnosis = $diagnosis; // TODO remove
    }

    protected function model()
    {
        return Drug::class;
    }

    protected function configName()
    {
        return 'drug';
    }
}