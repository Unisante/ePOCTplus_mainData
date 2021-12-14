<?php

namespace App\Services;

use App\Drug;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Config;

class DrugLoader extends ModelLoader
{
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
    public function __construct($drugData, $diagnosis = null, $duration = null)
    {
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
        if (is_array($this->duration)) {
            $duration = $this->duration[Config::get('medal.global.language')];
        } else {
            $duration = $this->duration;
        }
        return array_merge(parent::getValues(), [
            'duration' => $duration,
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

    /**
     *
     * Create a Drug instance based on the data that was provided
     *
     * @return Drug
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
