<?php

namespace App\Services;

use App\Diagnosis;
use App\Services\ModelLoader;

class DiagnosisLoader extends ModelLoader {
    protected $diagnosisData;
    protected $version;

    /**
     * Undocumented function
     *
     * @param object $drugData
     * @param Version $version
     */
    public function __construct($diagnosisData, $version) {
        $this->diagnosisData = $diagnosisData;
        $this->version = $version;
    }

    public function getKeys()
    {
        return [
            'medal_c_id' => $this->diagnosisData['id'],
            'diagnostic_id' => $this->diagnosisData['diagnostic_id']
        ];
    }

    public function getValues()
    {
        return [
            'label' => $this->diagnosisData['label'][env('LANGUAGE')],
            'type' => $this->diagnosisData['type'],
            'version_id' => $this->version->id,
        ];
    }

    public function model()
    {
        return Diagnosis::class;
    }
}