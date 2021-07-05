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
        $this->patientData = $patientData;
        $this->nodesData = $nodesData;
        $this->patientConfig = $patientConfig;
        $this->consentFileName = $consentFileName;
    }

    public function getKeys()
    {
        return [
            'local_patient_id' => $this->patientData['uid']
        ];
    }

    public function getValues()
    {
        return [
            'first_name' => $this->nodeValue('first_name_question_id'),
            'middle_name' => $this->nodeValueOrDefault('middle_name_patient_id', ''),
            'last_name' => $this->nodeValue('last_name_question_id'),
            'birthdate' => $this->nodeValue('birth_date_question_id'),
            'weight' => $this->nodeValue('weight_question_id'),
            'gender' => $this->nodeValue('gender_question_id'),
            'group_id' => $this->patientData['group_id'],
            'other_group_id' => $this->patientData['other_group_id'],
            'other_study_id' => $this->patientData['other_study_id'],
            'other_uid' => $this->patientData['other_uid'],
            'other_id' => $this->nodeValueOrDefault('other_id_patient_id', ''),
            'consent' => $this->consentFileName, // TODO
            'duplicate' => $this->hasDuplicate,
            'related_ids' => [$this->patientData['other_uid']], // TODO this value makes no sense
            'created_at' => new DateTime($this->patientData['created_at']),
            'updated_at' => new DateTime($this->patientData['updated_at']),
        ];
    }

    public function model()
    {
        return Patient::class;
    }

    public function getDuplicateConditions()
    {
        return array_filter([
            'first_name' => $this->nodeValue('first_name_question_id'),
            'middle_name' => $this->nodeValueOrDefault('middle_name_patient_id', ''),
            'last_name' => $this->nodeValue('last_name_question_id'),
            'birthdate' => $this->nodeValue('birth_date_question_id'),
            'other_id' => $this->nodeValueOrDefault('other_id_patient_id', ''),
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