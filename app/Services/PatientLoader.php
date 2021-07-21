<?php

namespace App\Services;

use App\Patient;
use App\Services\ModelLoader;
use DateTime;

class PatientLoader extends ModelLoader {
    protected $patientData;
    protected $nodesData;
    protected $patientConfig;
    protected $consentFileName;
    protected $hasDuplicate = false;

    /**
     * Undocumented function
     *
     * @param object $drugData
     * @param object $nodesData
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
        // TODO how to validate nodes data?
        return array_merge(parent::getValues(), [
            //'related_ids' => [$this->valueFromConfig('values', 'related_ids')], // TODO this value makes no sense
            'middle_name' => $this->nodeValueOrDefault('middle_name_patient_id', ''),
            'weight' => $this->nodeValue('weight_question_id'),
            'gender' => $this->nodeValue('gender_question_id'),
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
     * Undocumented function
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