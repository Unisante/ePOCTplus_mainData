<?php

namespace App\Services;

use App\Activity;
use App\Algorithm;
use App\Node;
use App\Services\ModelLoader;

class ActivityLoader extends ModelLoader {
    protected $activitiesData;
    protected $medicalCase;

    /**
     * Constructor
     *
     * @param array $activitiesData
     */
    public function __construct($activitiesData, $medicalCase) {
      parent::__construct($activitiesData);

      $this->activitiesData = $activitiesData;
      $this->medicalCase = $medicalCase;

    }

    protected function getValues()
    {
      return array_merge(parent::getValues(), [
        'medical_case_id' => $this->medicalCase->id
      ]);
    }

    protected function model()
    {
        return Activity::class;
    }

    protected function configName()
    {
        return 'activities';
    }
}
