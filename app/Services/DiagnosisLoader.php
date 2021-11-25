<?php

namespace App\Services;

use App\Diagnosis;
use App\Services\ModelLoader;

class DiagnosisLoader extends ModelLoader {
    protected $diagnosisData;
    protected $version;

    /**
     * Constructor
     *
     * @param array $drugData
     * @param Version $version
     */
    public function __construct($diagnosisData, $version) {
        parent::__construct($diagnosisData);
        $this->diagnosisData = $diagnosisData;
        $this->version = $version;
    }

    protected function getValues()
    {
        return array_merge(parent::getValues(), [
            'version_id' => $this->version->id,
        ]);
    }

    protected function model()
    {
        return Diagnosis::class;
    }

    protected function configName()
    {
        return 'diagnosis';
    }
}