<?php

namespace App\Services;

use App\Patient;
use App\Services\ModelLoader;
use Illuminate\Support\Facades\Log;

class PatientLoader extends ModelLoader {
    protected $patientData;
    protected $nodesData;
    protected $patientConfig;
    protected $consentFileName;
    protected $hasDuplicate = false;

    /**
     * Constructor
     *
     * @param array $drugData
     * @param array $nodesData
     * @param PatientConfig $version
     * @param string $consentFileName
     */
    public function __construct($patientData, $nodesData, $patientConfig, $consentFileName) {
        parent::__construct($patientData);
        $this->patientData = $patientData;
        $this->nodesData = $nodesData;
        $this->patientConfig = $patientConfig;
        $this->consentFileName = $consentFileName;
    }

    protected function getValues()
    {
        $valuesFromConfig = parent::getValues();
        return array_merge($valuesFromConfig, [
            'related_ids' => $valuesFromConfig['other_uid'] === null ? [] : [$valuesFromConfig['other_uid']],
            'middle_name' => $this->nodeValueOrDefault('middle_name_patient_id', ''),
            'gender' => $this->nodeValue('gender_patient_id'),
            'other_id' => $this->nodeValueOrDefault('other_id_patient_id', ''),
            'consent' => $this->consentFileName,
            'duplicate' => $this->hasDuplicate,
        ]);
    }

    protected function model()
    {
        return Patient::class;
    }

    protected function configName()
    {
        return 'patient';
    }

    public function getDuplicateConditions()
    {
        $values = $this->getValues();
        return array_filter([
            'first_name' => $values['first_name'],
            'middle_name' => $values['middle_name'],
            'last_name' => $values['last_name'],
            'birthdate' => $values['birthdate'],
            'other_id' => $values['other_id'],
        ]);
    }

    public function getExistingPatientAnswer()
    {
        return [
            'medal_c_id' => $this->nodesData[$this->patientConfig->config['parent_in_study_id']]['answer']
        ];
    }

    /**
     * Flag the patient as duplicate if one or more conditions are true
     *
     * @param bool $duplicateDataExists
     * @param bool $existingPatientIsTrue
     * @return void
     */
    public function flagAsDuplicate($duplicateDataExists, $existingPatientIsTrue) {
        $this->hasDuplicate = $duplicateDataExists || $existingPatientIsTrue || $this->patientData['other_uid'];
    }

    protected function nodeValue($configKey)
    {
        return $this->nodesData[$this->patientConfig->config[$configKey]]['value'];
    }

    protected function nodeValueOrDefault($configKey, $default)
    {
        $nodeKey = $this->patientConfig->config[$configKey];
        return isset($this->nodesData[$nodeKey]) ? $this->nodesData[$nodeKey]['value'] : $default;
    }
}