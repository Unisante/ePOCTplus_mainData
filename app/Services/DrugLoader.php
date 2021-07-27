<?php

namespace App\Services;

use App\Drug;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Log;

class DrugLoader extends ModelLoader {
    protected $drugData;
    protected $diagnosis;
    protected $duration;

    /**
     * Constructor
     *
     * @param object $drugData
     * @param Diagnosis $diagnosis
     * @param string $duration
     */
    public function __construct($drugData, $diagnosis = null, $duration = null) {
        parent::__construct($drugData);
        $this->drugData = $drugData;
        $this->diagnosis = $diagnosis;
        $this->duration = $duration;
    }

    protected function getKeys()
    {
        return array_merge(parent::getKeys(), [
            'diagnosis_id' => $this->diagnosis->id ?? null,
        ]);
    }

    protected function getValues()
    {
        return array_merge(parent::getValues(), [
            'duration' => $this->duration,
        ]);
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