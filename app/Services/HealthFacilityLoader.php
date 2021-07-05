<?php

namespace App\Services;

use App\HealthFacility;
use App\Services\ModelLoader;

class HealthFacilityLoader extends ModelLoader {
    protected $healthFacilityData;

    /**
     * Undocumented function
     *
     * @param object $formulationData
     */
    public function __construct($healthFacilityData) {
        $this->healthFacilityData = $healthFacilityData;
    }

    public function getKeys()
    {
        return [
            'facility_name' => $this->healthFacilityData['name']            
        ];
    }

    public function getValues()
    {
        return [
            'group_id'=> $this->healthFacilityData['id'],
            'long'=> $this->healthFacilityData['longitude'],
            'lat'=> $this->healthFacilityData['latitude'],
            'hf_mode'=> $this->healthFacilityData['architecture']
        ];
    }

    public function model()
    {
        return HealthFacility::class;
    }
}