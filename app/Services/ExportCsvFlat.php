<?php

namespace App\Services;

use App\Version;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ExportCsvFlat extends ExportCsv
{

    protected static $DIAGNOSIS_NOT_PROPOSED = null;
    protected static $DIAGNOSIS_PROPOSED_AND_ACCEPTED = "Accepted";
    protected static $DIAGNOSIS_PROPOSED_AND_REJECTED = "Rejected";
    protected static $DIAGNOSIS_MANUALLY_ADDED = "Manually added";
    protected static $VARIABLE_DEFAULT_VALUE = null;
    protected static $DRUG_NOT_PROPOSED = null;
    protected static $DRUG_PROPOSED_AND_ACCEPTED = "Accepted";
    protected static $DRUG_PROPOSED_AND_REJECTED = "Rejected";
    protected static $DRUG_MANUALLY_ADDED = "Manually added";

    /**
     * Constructor
     */
    public function __construct($medical_cases, $from_date, $to_date, $chunk_key)
    {
        parent::__construct($medical_cases, $from_date, $to_date, $chunk_key);
    }

    /**
     * @param Patient $patient
     * @return Collection patient data
     */
    protected static function getPatientData($patient, $consultation_date)
    {
        return [
            Config::get('csv.flat.identifiers.patient.dyn_pat_study_id_patient') => $patient->id,
            // Config::get('csv.flat.identifiers.patient.dyn_pat_first_name') => $patient->first_name,
            // Config::get('csv.flat.identifiers.patient.dyn_pat_last_name') => $patient->last_name,
            Config::get('csv.flat.identifiers.patient.dyn_pat_birth_date') => Carbon::parse($patient->birthdate)->diffInDays(Carbon::parse($consultation_date)),
            Config::get('csv.flat.identifiers.patient.dyn_pat_gender') => $patient->gender,
            Config::get('csv.flat.identifiers.patient.dyn_pat_local_patient_id') => $patient->local_patient_id,
            Config::get('csv.flat.identifiers.patient.dyn_pat_group_id') => $patient->group_id,
            Config::get('csv.flat.identifiers.patient.dyn_pat_consent') => $patient->consent,
            Config::get('csv.flat.identifiers.patient.dyn_pat_redcap') => $patient->redcap,
            Config::get('csv.flat.identifiers.patient.dyn_pat_duplicate') => $patient->duplicate,
            Config::get('csv.flat.identifiers.patient.dyn_pat_other_uid') => $patient->other_uid,
            Config::get('csv.flat.identifiers.patient.dyn_pat_other_study_id') => $patient->other_study_id,
            Config::get('csv.flat.identifiers.patient.dyn_pat_other_group_id') => $patient->other_group_id,
            Config::get('csv.flat.identifiers.patient.dyn_pat_merged_with') => $patient->merged_with,
            Config::get('csv.flat.identifiers.patient.dyn_pat_merged') => $patient->merged,
            Config::get('csv.flat.identifiers.patient.dyn_pat_status') => $patient->status,
            Config::get('csv.flat.identifiers.patient.dyn_pat_related_ids') => $patient->related_ids,
            Config::get('csv.flat.identifiers.patient.dyn_pat_middle_name') => $patient->middle_name,
            Config::get('csv.flat.identifiers.patient.dyn_pat_other_id') => $patient->other_id,
        ];
    }

    /**
     * @param MedicalCase $medical_case
     * @return Collection medical case data
     */
    protected static function getMedicalCaseData($medical_case)
    {
        return [
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_id') => $medical_case->id,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_local_medical_case_id') => $medical_case->local_medical_case_id,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_consent') => $medical_case->consent,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_isEligible') => $medical_case->isEligible,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_redcap') => $medical_case->redcap,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_consultation_month') => Carbon::parse($medical_case->consultation_date)->format('F'),
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_consultation_day') => Carbon::parse($medical_case->consultation_date)->format('l'),
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_closedAt') => $medical_case->closedAt,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_force_close') => $medical_case->force_close,
            Config::get('csv.flat.identifiers.medical_case.dyn_mc_mc_redcap_flag') => $medical_case->mc_redcap_flag,
        ];
    }

    protected static function getHealthFacilityData($health_facility)
    {
        return [
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_id') => $health_facility->id ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_group_id') => $health_facility->group_id ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_long') => $health_facility->long ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_lat') => $health_facility->lat ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_hf_mode') => $health_facility->hf_mode ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_name') => $health_facility->name ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_country') => $health_facility->country ?? '',
            Config::get('csv.flat.identifiers.health_facility.dyn_hfa_area') => $health_facility->area ?? '',
        ];
    }

    protected static function getDeviceData($device)
    {
        return [
            Config::get('csv.flat.identifiers.device.dyn_device_id') => $device->id ?? '',
            Config::get('csv.flat.identifiers.device.dyn_device_name') => $device->name ?? '',
            Config::get('csv.flat.identifiers.device.dyn_device_health_facility_id') => $device->health_facility_id ?? '',
            Config::get('csv.flat.identifiers.device.dyn_device_mac_address') => $device->mac_address ?? '',
            Config::get('csv.flat.identifiers.device.dyn_device_last_seen') => $device->last_seen ?? '',
        ];
    }

    /**
     * @param  $version
     * @return Collection version data
     */
    protected static function getVersionData($version)
    {
        return [
            Config::get('csv.flat.identifiers.version.dyn_ver_id') => $version->id,
            Config::get('csv.flat.identifiers.version.dyn_ver_medal_c_id') => $version->medal_c_id,
            Config::get('csv.flat.identifiers.version.dyn_ver_name') => $version->name,
            Config::get('csv.flat.identifiers.version.dyn_ver_consent_management') => $version->consent_management,
            Config::get('csv.flat.identifiers.version.dyn_ver_study') => $version->study,
            Config::get('csv.flat.identifiers.version.dyn_ver_is_arm_control') => $version->is_arm_control,
        ];
    }

    /**
     * @param  $algorithm
     * @return Collection algorithm data
     */
    protected static function getAlgorithmData($algorithm)
    {
        return [
            Config::get('csv.flat.identifiers.algorithm.dyn_alg_id') => $algorithm->id,
            Config::get('csv.flat.identifiers.algorithm.dyn_alg_name') => $algorithm->name,
        ];
    }

    /**
     * @param  $activity
     * @return Collection activity data
     */
    protected static function getActivityData($activity)
    {
        return [
            Config::get('csv.flat.identifiers.activity.dyn_act_id') => $activity->id,
            Config::get('csv.flat.identifiers.activity.dyn_act_medical_case_id') => $activity->medical_case_id,
            Config::get('csv.flat.identifiers.activity.dyn_act_medal_c_id') => $activity->medal_c_id,
            Config::get('csv.flat.identifiers.activity.dyn_act_step') => $activity->step,
            Config::get('csv.flat.identifiers.activity.dyn_act_clinician') => $activity->clinician,
            Config::get('csv.flat.identifiers.activity.dyn_act_mac_address') => $activity->mac_address,
            Config::get('csv.flat.identifiers.activity.dyn_act_created_at') => $activity->created_at,
            Config::get('csv.flat.identifiers.activity.dyn_act_updated_at') => $activity->updated_at,
        ];
    }

    /**
     * @param  $diagnosis
     * @return Collection diagnosis data
     */
    protected static function getDiagnosisData($diagnosis)
    {
        return [
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_id') => $diagnosis->id,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_medal_c_id') => $diagnosis->medal_c_id,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_label') => $diagnosis->label,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_diagnostic_id') => $diagnosis->diagnostic_id,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_created_at') => $diagnosis->created_at,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_updated_at') => $diagnosis->updated_at,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_type') => $diagnosis->type,
            Config::get('csv.flat.identifiers.diagnosis.dyn_dia_version_id') => $diagnosis->version_id,
        ];
    }

    /**
     * @param  $custom_diagnosis
     * @return Collection custom diagnosis data
     */
    protected static function getCustomDiagnosisData($custom_diagnosis)
    {
        return [
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_id') => $custom_diagnosis->id,
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_label') => $custom_diagnosis->label,
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_drugs') => $custom_diagnosis->drugs,
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_created_at') => $custom_diagnosis->created_at,
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_updated_at') => $custom_diagnosis->updated_at,
            Config::get('csv.flat.identifiers.custom_diagnosis.dyn_cdi_medical_case_id') => $custom_diagnosis->medical_case_id,
        ];
    }

    /**
     * @param  $diagnosis_reference
     * @return Collection diagnosis reference data
     */
    protected static function getDiagnosisReferenceData($diagnosis_reference)
    {
        return [
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_id') => $diagnosis_reference->id,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_agreed') => $diagnosis_reference->agreed,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_additional') => $diagnosis_reference->additional,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_diagnosis_id') => $diagnosis_reference->diagnosis_id,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_medical_case_id') => $diagnosis_reference->medical_case_id,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_created_at') => $diagnosis_reference->created_at,
            Config::get('csv.flat.identifiers.diagnosis_reference.dyn_dre_updated_at') => $diagnosis_reference->updated_at,
        ];
    }

    /**
     * @param  $drug
     * @return Collection drug data
     */
    protected static function getDrugData($drug)
    {
        return [
            Config::get('csv.flat.identifiers.drug.dyn_dru_id') => $drug->id,
            Config::get('csv.flat.identifiers.drug.dyn_dru_medal_c_id') => $drug->medal_c_id,
            Config::get('csv.flat.identifiers.drug.dyn_dru_type') => $drug->type,
            Config::get('csv.flat.identifiers.drug.dyn_dru_label') => $drug->label,
            Config::get('csv.flat.identifiers.drug.dyn_dru_description') => $drug->description,
            Config::get('csv.flat.identifiers.drug.dyn_dru_diagnosis_id') => $drug->diagnosis_id,
            Config::get('csv.flat.identifiers.drug.dyn_dru_created_at') => $drug->created_at,
            Config::get('csv.flat.identifiers.drug.dyn_dru_updated_at') => $drug->updated_at,
            Config::get('csv.flat.identifiers.drug.dyn_dru_is_anti_malarial') => $drug->is_anti_malarial,
            Config::get('csv.flat.identifiers.drug.dyn_dru_is_antibiotic') => $drug->is_antibiotic,
            Config::get('csv.flat.identifiers.drug.dyn_dru_duration') => $drug->duration,
        ];
    }

    /**
     * @param  $additional_drug
     * @return Collection additional drug data
     */
    protected static function getAdditionalDrugData($additional_drug)
    {
        return [
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_id') => $additional_drug->id,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_drug_id') => $additional_drug->drug_id,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_medical_case_id') => $additional_drug->medical_case_id,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_formulationSelected') => $additional_drug->formulationSelected,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_agreed') => $additional_drug->agreed,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_version_id') => $additional_drug->version_id,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_created_at') => $additional_drug->created_at,
            Config::get('csv.flat.identifiers.additional_drug.dyn_adr_updated_at') => $additional_drug->updated_at,
        ];
    }

    /**
     * @param  $drug_reference
     * @return Collection drug reference data
     */
    protected static function getDrugReferenceData($drug_reference)
    {
        return [
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_id') => $drug_reference->id,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_drug_id') => $drug_reference->drug_id,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_diagnosis_id') => $drug_reference->diagnosis_id,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_agreed') => $drug_reference->agreed,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_created_at') => $drug_reference->created_at,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_updated_at') => $drug_reference->updated_at,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_formulationSelected') => $drug_reference->formulationSelected,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_formulation_id') => $drug_reference->formulation_id,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_additional') => $drug_reference->additional,
            Config::get('csv.flat.identifiers.drug_reference.dyn_dre_duration') => $drug_reference->duration,
        ];
    }

    /**
     * @param  $management
     * @return Collection management data
     */
    protected static function getManagementData($management)
    {
        return [
            Config::get('csv.flat.identifiers.management.dyn_man_id') => $management->id,
            Config::get('csv.flat.identifiers.management.dyn_man_drug_id') => $management->drug_id,
            Config::get('csv.flat.identifiers.management.dyn_man_diagnosis_id') => $management->diagnosis_id,
            Config::get('csv.flat.identifiers.management.dyn_man_agreed') => $management->agreed,
            Config::get('csv.flat.identifiers.management.dyn_man_created_at') => $management->created_at,
            Config::get('csv.flat.identifiers.management.dyn_man_updated_at') => $management->updated_at,
            Config::get('csv.flat.identifiers.management.dyn_man_formulationSelected') => $management->formulationSelected,
            Config::get('csv.flat.identifiers.management.dyn_man_formulation_id') => $management->formulation_id,
            Config::get('csv.flat.identifiers.management.dyn_man_additional') => $management->additional,
            Config::get('csv.flat.identifiers.management.dyn_man_duration') => $management->duration,
        ];
    }

    /**
     * @param  $management_reference
     * @return Collection management reference data
     */
    protected static function getManagementReferenceData($management_reference)
    {
        return [
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_id') => $management_reference->id,
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_agreed') => $management_reference->agreed,
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_diagnosis_id') => $management_reference->diagnosis_id,
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_created_at') => $management_reference->created_at,
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_updated_at') => $management_reference->updated_at,
            Config::get('csv.flat.identifiers.management_reference.dyn_mre_management_id') => $management_reference->management_id,
        ];
    }

    /**
     * @param  $formulation
     * @return Collection formulation data
     */
    protected static function getFormulationData($formulation)
    {
        return [
            Config::get('csv.flat.identifiers.formulation.dyn_for_id') => $formulation->id,
            Config::get('csv.flat.identifiers.formulation.dyn_for_medical_form') => $formulation->medical_form,
            Config::get('csv.flat.identifiers.formulation.dyn_for_administration_route_name') => $formulation->administration_route_name,
            Config::get('csv.flat.identifiers.formulation.dyn_for_liquid_concentration') => $formulation->liquid_concentration,
            Config::get('csv.flat.identifiers.formulation.dyn_for_dose_form') => $formulation->dose_form,
            Config::get('csv.flat.identifiers.formulation.dyn_for_unique_dose') => $formulation->unique_dose,
            Config::get('csv.flat.identifiers.formulation.dyn_for_by_age') => $formulation->by_age,
            Config::get('csv.flat.identifiers.formulation.dyn_for_minimal_dose_per_kg') => $formulation->minimal_dose_per_kg,
            Config::get('csv.flat.identifiers.formulation.dyn_for_maximal_dose_per_kg') => $formulation->maximal_dose_per_kg,
            Config::get('csv.flat.identifiers.formulation.dyn_for_maximal_dose') => $formulation->maximal_dose,
            Config::get('csv.flat.identifiers.formulation.dyn_for_description') => $formulation->description,
            Config::get('csv.flat.identifiers.formulation.dyn_for_doses_per_day') => $formulation->doses_per_day,
            Config::get('csv.flat.identifiers.formulation.dyn_for_created_at') => $formulation->created_at,
            Config::get('csv.flat.identifiers.formulation.dyn_for_updated_at') => $formulation->updated_at,
            Config::get('csv.flat.identifiers.formulation.dyn_for_drug_id') => $formulation->drug_id,
            Config::get('csv.flat.identifiers.formulation.dyn_for_administration_route_category') => $formulation->administration_route_category,
            Config::get('csv.flat.identifiers.formulation.dyn_for_medal_c_id') => $formulation->medal_c_id,
        ];
    }

    /**
     * Adds a medical case to the data array.
     */
    protected function addPatientData(&$data, $index, $patient, $consultation_date)
    {
        $data[$index] = array_merge($data[$index], $this->getPatientData($patient, $consultation_date));
        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.patient')));
        }
    }

    /**
     * Adds a health facility to the data array.
     */
    protected function addHealthFacilityData(&$data, $index, $health_facility)
    {
        $data[$index] = array_merge($data[$index], $this->getHealthFacilityData($health_facility));
        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.health_facility')));
        }
    }

    /**
     * Adds a device to the data array.
     */
    protected function addDeviceData(&$data, $index, $device)
    {
        $data[$index] = array_merge($data[$index], $this->getDeviceData($device));

        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.device')));
        }
    }

    /**
     * Adds a medical case to the data array.
     */
    protected function addMedicalCaseData(&$data, $index, $medical_case)
    {
        $data[$index] = array_merge($data[$index], $this->getMedicalCaseData($medical_case));
        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.medical_case')));
        }
    }

    /**
     * @return Collection the list of default variables' values
     */
    protected static function getVariableDefaultValues($node_objs)
    {
        $variable_values = [];
        foreach ($node_objs as $node_obj) {
            $variable_values[$node_obj->id] = self::$VARIABLE_DEFAULT_VALUE;
        }

        return $variable_values;
    }

    /**
     * @return Collection the list of variables' labels
     */
    protected static function getVariableLabels($node_objs, $version_node_names)
    {
        $labels = [];
        foreach ($node_objs as $node_obj) {
            $label = $node_obj->label;
            $label = $version_node_names[trim($label)] ?? $label;
            $labels[] = "[" . $node_obj->category . "] " . $node_obj->id . ' - ' . $label;
        }

        return $labels;
    }

    /**
     * Adds a medical case answer to the data array.
     */
    protected function addMedicalCaseAnswerData(&$data, $index, $medical_case_answers, $version_node_names)
    {
        $node_objs = Cache::store('array')->rememberForever('node_objs', function () {
            return DB::table('nodes')->select('id', 'label', 'category')->get();
        });

        $variable_values = self::getVariableDefaultValues($node_objs);

        foreach ($medical_case_answers as $medical_case_answer) {
            if (self::isSkippedMedicalCaseAnswer($medical_case_answer)) {
                continue;
            }

            $node_id = $medical_case_answer->node_id;
            if ($medical_case_answer->answer) {
                $variable_values[$node_id] = $medical_case_answer->answer->label ?? null;
            } else {
                $variable_values[$node_id] = $medical_case_answer->value ?? null;
            }
        }
        $data[$index] = array_merge($data[$index], $variable_values);
        // add labels
        $labels = self::getVariableLabels($node_objs, $version_node_names);

        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $labels);
        }
    }

    /**
     * Adds a version answer to the data array.
     */
    protected function addVersionData(&$data, $index, $version)
    {
        $data[$index] = array_merge($data[$index], $this->getVersionData($version));
        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.version')));
        }
    }

    /**
     * Adds an algorithm to the data array.
     */
    protected function addAlgorithmData(&$data, $index, $algorithm)
    {
        $data[$index] = array_merge($data[$index], $this->getAlgorithmData($algorithm));
        if ($this->chunk_key == 1) {
            $data[0] = array_merge($data[0], $this->getAttributeList(Config::get('csv.flat.identifiers.algorithm')));
        }
    }

    /**
     * @return Collection the list of default diagnosis' values
     */
    protected static function getDiagnosisDefaultValues($diagnosis_objs)
    {
        $diagnosis_values = [];
        foreach ($diagnosis_objs as $diagnosis_obj) {
            $diagnosis_values[$diagnosis_obj->id] = self::$DIAGNOSIS_NOT_PROPOSED;
        }

        return $diagnosis_values;
    }

    /**
     * @return Collection the list of diagnosis' labels
     */
    protected static function getDiagnosisLabels($diagnosis_objs)
    {
        $labels = [];
        foreach ($diagnosis_objs as $diagnosis_obj) {
            $labels[] = "[Diagnosis] " . $diagnosis_obj->id . " - " . $diagnosis_obj->label;
        }

        return $labels;
    }

    /**
     * Adds diagnoses to the data array.
     */
    protected function addDiagnosesData(&$data, $index, $diagnosis_references)
    {
        $diagnosis_objs = Cache::store('array')->rememberForever('diagnosis_objs', function () {
            return DB::table('diagnoses')->select('id', 'label')->get();
        });
        $diagnosis_values = self::getDiagnosisDefaultValues($diagnosis_objs);

        foreach ($diagnosis_references as $diagnosis_reference) {
            if (self::isSkippedDiagnosisReference($diagnosis_reference)) {
                continue;
            }
            $diagnosis_id = $diagnosis_reference->diagnosis_id;
            if ($diagnosis_reference->additional) {
                $diagnosis_values[$diagnosis_id] = self::$DIAGNOSIS_MANUALLY_ADDED;
            } else {
                $diagnosis_values[$diagnosis_id] = $diagnosis_reference->agreed ? self::$DIAGNOSIS_PROPOSED_AND_ACCEPTED : self::$DIAGNOSIS_PROPOSED_AND_REJECTED;
            }
        }
        $data[$index] = array_merge($data[$index], $diagnosis_values);
        // add labels
        if ($this->chunk_key == 1) {
            $labels = self::getDiagnosisLabels($diagnosis_objs);
            $data[0] = array_merge($data[0], $labels);
        }
    }

    /**
     * Adds custom diagnoses to the data array.
     */
    protected function addCustomDiagnosesData(&$data, $index, $custom_diagnoses)
    {
        $custom_diagnosis_objs = Cache::store('array')->rememberForever('custom_diagnosis_objs', function () {
            return DB::table('custom_diagnoses')->select('id', 'label')->get();
        });
        $custom_diagnosis_values = self::getDiagnosisDefaultValues($custom_diagnosis_objs);

        foreach ($custom_diagnoses as $custom_diagnosis) {
            $id = $custom_diagnosis->id;
            $custom_diagnosis_values[$id] = self::$DIAGNOSIS_MANUALLY_ADDED;
        }

        $data[$index] = array_merge($data[$index], $custom_diagnosis_values);
        // add labels
        if ($this->chunk_key == 1) {
            $labels = self::getDiagnosisLabels($custom_diagnosis_objs);
            $data[0] = array_merge($data[0], $labels);
        }
    }

    /**
     * @return Collection the list of default drugs' values
     */
    protected static function getDrugDefaultValues($drug_objs)
    {
        $drug_values = [];
        foreach ($drug_objs as $drug_obj) {
            $drug_values[$drug_obj->id] = self::$DRUG_NOT_PROPOSED;
        }

        return $drug_values;
    }

    /**
     * @return Collection the list of drugs' labels
     */
    protected static function getDrugLabels($drug_objs)
    {
        $labels = [];
        foreach ($drug_objs as $drug_obj) {
            $labels[] = "[Drug] " . $drug_obj->id . " - " . $drug_obj->label;
        }

        return $labels;
    }

    /**
     * Adds drugs to the data array.
     */
    protected function addDrugData(&$data, $index, $diagnosis_references)
    {

        $drug_objs = Cache::store('array')->rememberForever('drug_objs', function () {
            return DB::table('drugs')->select('id', 'label')->get();
        });
        $drug_values = self::getDrugDefaultValues($drug_objs);

        foreach ($diagnosis_references as $diagnosis_reference) {
            $drug_references = $diagnosis_reference->drug_references;
            foreach ($drug_references as $drug_reference) {
                $agreed = $drug_reference->agreed;
                $additional = $drug_reference->additional;
                $drug_id = $drug_reference->drug_id;
                if ($additional) {
                    $drug_values[$drug_id] = self::$DRUG_MANUALLY_ADDED;
                } else {
                    $drug_values[$drug_id] = $agreed ? self::$DRUG_PROPOSED_AND_ACCEPTED : self::$DRUG_PROPOSED_AND_REJECTED;
                }
            }
        }

        $data[$index] = array_merge($data[$index], $drug_values);
        // add labels
        if ($this->chunk_key == 1) {
            $labels = self::getDrugLabels($drug_objs);
            $data[0] = array_merge($data[0], $labels);
        }
    }

    protected static function getCustomDrugsLabels($custom_drugs_objs)
    {
        $labels = [];
        foreach ($custom_drugs_objs as $custom_drug_obj) {
            $labels[] = "[Drug] " . $custom_drug_obj->id . " - " . $custom_drug_obj->name;
        }

        return $labels;
    }

    /**
     * Adds custom drugs to the data array.
     */
    protected function addCustomDrugData(&$data, $index, $custom_diagnoses)
    {
        $custom_drugs_objs = Cache::store('array')->rememberForever('custom_drugs_objs', function () {
            return DB::table('custom_drugs')->select('id', 'name')->get();
        });

        $custom_drugs_values = self::getDrugDefaultValues($custom_drugs_objs);

        foreach ($custom_diagnoses as $custom_diagnosis) {
            $custom_drugs = $custom_diagnosis->custom_drugs;
            foreach ($custom_drugs as $custom_drug) {
                $id = $custom_drug->id;
                $custom_drugs_values[$id] = self::$DRUG_MANUALLY_ADDED;
            }
        }

        $data[$index] = array_merge($data[$index], $custom_drugs_values);
        // add labels
        if ($this->chunk_key == 1) {
            $labels = self::getCustomDrugsLabels($custom_drugs_objs);
            $data[0] = array_merge($data[0], $labels);
        }
    }

    /**
     * Adds all children to the variables data. Recursive.
     */
    protected function addChildrenToArray(&$data, $json_children, $id)
    {
        foreach ($json_children as $json_child) {
            if (in_array('children', array_keys($json_child))) {
                $json_child_children = $json_child['children'];
                $this->addChildrenToArray($data, $json_child_children, $id); // recursive search
            } else {
                $variable_name = trim(strstr($json_child['title'], " "));
                $variable_name = trim(strstr($variable_name, " "));
                $data[$id][$variable_name] = $json_child['title'];
            }
        }
    }

    /**
     * Get the informations of nodes references for every version.
     * Messy function to retrieve the variables' references. Should be a specific field in the future.
     */
    protected function getJsonNodesInfo()
    {

        return Cache::store('array')->rememberForever('versions_data', function () {

            $versions = Version::all();

            $versions_data = [];
            foreach ($versions as $version) {
                $id = $version->medal_c_id;
                $versions_data[$id] = [];

                ini_set("allow_url_fopen", 1);
                $json = file_get_contents('https://medalc.unisante.ch/api/v1/versions/' . $version->medal_c_id);
                $obj = json_decode($json);
                $json_steps = json_decode($obj->full_order_json, true);
                foreach ($json_steps as $json_step) {
                    $json_children = $json_step['children'];
                    $this->addChildrenToArray($versions_data, $json_children, $id);
                }
            }
            return $versions_data;
        });

    }

    /**
     * Retrieve all the data.
     */
    protected function getDataFromMedicalCases()
    {
        $data = [];
        if ($this->chunk_key == 1) {
            $data[] = []; // list of attributes.
        }

        $version_node_names = $this->getJsonNodesInfo();

        foreach ($this->medical_cases as $medical_case) {
            $patient = $medical_case->patient;
            $index = $patient->id . '-' . $medical_case->id;
            $data[$index] = [];
            // get medical case data
            $this->addMedicalCaseData($data, $index, $medical_case);

            // get patient data
            $this->addPatientData($data, $index, $patient, $medical_case->consultation_date);

            $health_facility = $medical_case->patient->facility;

            // get health facility
            $this->addHealthFacilityData($data, $index, $health_facility);

            $device = $health_facility->devices;
            // get device
            $this->addDeviceData($data, $index, $device->first());

            $version = $medical_case->version;
            // get version data
            $this->addVersionData($data, $index, $version);

            $algorithm = $version->algorithm;
            // get algorithm data
            $this->addAlgorithmData($data, $index, $algorithm);

            $medical_case_answers = $medical_case->medical_case_answers;
            // get medical case answer data
            $this->addMedicalCaseAnswerData($data, $index, $medical_case_answers, $version_node_names[$version->medal_c_id]);

            $diagnosis_references = $medical_case->diagnoses_references;
            // get diagnosis data
            $this->addDiagnosesData($data, $index, $diagnosis_references);

            $custom_diagnoses = $medical_case->custom_diagnoses;
            // get custom diagnosis data
            $this->addCustomDiagnosesData($data, $index, $custom_diagnoses);

            // get drug data
            $this->addDrugData($data, $index, $diagnosis_references);

            // get custom drug data
            $this->addCustomDrugData($data, $index, $custom_diagnoses);
        }

        if ($this->chunk_key == 1) {
            $data[0] = array_unique($data[0]);
        }
        return $data;
    }

    public function export()
    {
        $data = $this->getDataFromMedicalCases();
        $folder = storage_path('app/export/' . Config::get('csv.flat.folder'));
        if (!File::exists($folder)) {
            File::makeDirectory($folder);
        }
        $file = fopen($folder . 'answers.csv', "a+");
        foreach ($data as $line) {
            $attributes = $this->attributesToStr((array) $line);
            fputcsv($file, $attributes);
        }
        fclose($file);
    }
}
