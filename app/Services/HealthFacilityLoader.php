<?php

namespace App\Services;

use App\HealthFacility;
use App\Services\ModelLoader;

class HealthFacilityLoader extends ModelLoader {
    protected function model() { return HealthFacility::class; }
    protected function configName() { return 'health_facility'; }
}