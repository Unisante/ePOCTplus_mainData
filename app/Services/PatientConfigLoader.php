<?php

namespace App\Services;

use App\PatientConfig;

class PatientConfigLoader extends ModelLoader
{
    protected $data;
    protected $version;

    /**
     * Constructor
     *
     * @param array $data
     * @param Version $version
     */
    public function __construct($data, $version)
    {
        $this->data = $data;
        $this->version = $version;
    }

    protected function getKeys()
    {
        return [
            'version_id' => $this->version->id,
        ];
    }

    protected function getValues()
    {
        $filteredData = array_filter((array) $this->data, function ($k) {return $k != "study_id";}, ARRAY_FILTER_USE_KEY);

        // for some reason, some of the ids are passed as strings
        $filteredDataAsInts = array_map(function ($v) {return (int) $v;}, $filteredData);
        return [
            'config' => $filteredDataAsInts,
        ];
    }

    protected function model()
    {
        return PatientConfig::class;
    }

    protected function configName()
    {
        return 'patient_config';
    }

    /**
     * Create a PatientConfigLoader instance based on the data that was provided
     *
     * @return PatientConfigLoader
     */
    public function load()
    {
        $record = $this->model()::updateOrCreate(
            $this->getKeys(),
            $this->getValues()
        );
        $record->save();
        return $record;
    }
}
