<?php

namespace App\Services;

use App\AnswerType;
use App\Services\ExportCsv;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ExportCsvSeparate extends ExportCsv
{

    /**
     * Constructor
     * @param Collection medical_cases, medical cases to export
     * @param DateTime from_date, the starting date
     * @param DateTime to_date, the ending date
     */
    public function __construct($medical_cases, $from_date, $to_date, $chunk_key)
    {
        parent::__construct($medical_cases, $from_date, $to_date, $chunk_key);
    }

    /**
     * @param Patient $patient
     * @return Collection patient data
     */
    protected static function getPatientData($patient)
    {
        return [
            Config::get('csv.identifiers.patient.dyn_pat_study_id_patient') => $patient->id,
            Config::get('csv.identifiers.patient.dyn_pat_first_name') => $patient->first_name,
            Config::get('csv.identifiers.patient.dyn_pat_last_name') => $patient->last_name,
            Config::get('csv.identifiers.patient.dyn_pat_created_at') => $patient->created_at,
            Config::get('csv.identifiers.patient.dyn_pat_updated_at') => $patient->updated_at,
            Config::get('csv.identifiers.patient.dyn_pat_birth_date') => $patient->birthdate,
            Config::get('csv.identifiers.patient.dyn_pat_gender') => $patient->gender,
            Config::get('csv.identifiers.patient.dyn_pat_local_patient_id') => $patient->local_patient_id,
            Config::get('csv.identifiers.patient.dyn_pat_group_id') => $patient->group_id,
            Config::get('csv.identifiers.patient.dyn_pat_consent') => $patient->consent,
            Config::get('csv.identifiers.patient.dyn_pat_redcap') => $patient->redcap,
            Config::get('csv.identifiers.patient.dyn_pat_duplicate') => $patient->duplicate,
            Config::get('csv.identifiers.patient.dyn_pat_other_uid') => $patient->other_uid,
            Config::get('csv.identifiers.patient.dyn_pat_other_study_id') => $patient->other_study_id,
            Config::get('csv.identifiers.patient.dyn_pat_other_group_id') => $patient->other_group_id,
            Config::get('csv.identifiers.patient.dyn_pat_merged_with') => $patient->merged_with,
            Config::get('csv.identifiers.patient.dyn_pat_merged') => $patient->merged,
            Config::get('csv.identifiers.patient.dyn_pat_status') => $patient->status,
            Config::get('csv.identifiers.patient.dyn_pat_related_ids') => $patient->related_ids,
            Config::get('csv.identifiers.patient.dyn_pat_middle_name') => $patient->middle_name,
            Config::get('csv.identifiers.patient.dyn_pat_other_id') => $patient->other_id,
        ];
    }

    /**
     * @param MedicalCase $medical_case
     * @return Collection medical case data
     */
    protected static function getMedicalCaseData($medical_case)
    {
        return [
            Config::get('csv.identifiers.medical_case.dyn_mc_id') => $medical_case->id,
            Config::get('csv.identifiers.medical_case.dyn_mc_version_id') => $medical_case->version_id,
            Config::get('csv.identifiers.medical_case.dyn_mc_patient_id') => $medical_case->patient_id,
            Config::get('csv.identifiers.medical_case.dyn_mc_created_at') => $medical_case->created_at,
            Config::get('csv.identifiers.medical_case.dyn_mc_updated_at') => $medical_case->updated_at,
            Config::get('csv.identifiers.medical_case.dyn_mc_local_medical_case_id') => $medical_case->local_medical_case_id,
            Config::get('csv.identifiers.medical_case.dyn_mc_consent') => $medical_case->consent,
            Config::get('csv.identifiers.medical_case.dyn_mc_isEligible') => $medical_case->isEligible,
            Config::get('csv.identifiers.medical_case.dyn_mc_group_id') => $medical_case->patient->group_id,
            Config::get('csv.identifiers.medical_case.dyn_mc_redcap') => $medical_case->redcap,
            Config::get('csv.identifiers.medical_case.dyn_mc_consultation_date') => $medical_case->consultation_date,
            Config::get('csv.identifiers.medical_case.dyn_mc_closedAt') => $medical_case->closedAt,
            Config::get('csv.identifiers.medical_case.dyn_mc_force_close') => $medical_case->force_close,
            Config::get('csv.identifiers.medical_case.dyn_mc_mc_redcap_flag') => $medical_case->mc_redcap_flag,
        ];
    }

    /**
     * @param MedicalCase $medical_case_answer
     * @return Collection medical case answer data
     */
    protected static function getMedicalCaseAnswerData($medical_case_answer)
    {
        return [
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_id') => $medical_case_answer->id,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_medical_case_id') => $medical_case_answer->medical_case_id,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_answer_id') => $medical_case_answer->answer_id,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_node_id') => $medical_case_answer->node_id,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_value') => $medical_case_answer->value,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_created_at') => $medical_case_answer->created_at,
            Config::get('csv.identifiers.medical_case_answer.dyn_mca_updated_at') => $medical_case_answer->updated_at,
        ];
    }

    /**
     * @param  $node
     * @return Collection node data
     */
    protected static function getNodeData($node)
    {
        return [
            Config::get('csv.identifiers.node.dyn_nod_id') => $node->id,
            Config::get('csv.identifiers.node.dyn_nod_medal_c_id') => $node->medal_c_id,
            Config::get('csv.identifiers.node.dyn_nod_reference') => $node->reference,
            Config::get('csv.identifiers.node.dyn_nod_label') => $node->label,
            Config::get('csv.identifiers.node.dyn_nod_type') => $node->type,
            Config::get('csv.identifiers.node.dyn_nod_category') => $node->category,
            Config::get('csv.identifiers.node.dyn_nod_priority') => $node->priority,
            Config::get('csv.identifiers.node.dyn_nod_stage') => $node->stage,
            Config::get('csv.identifiers.node.dyn_nod_description') => $node->description,
            Config::get('csv.identifiers.node.dyn_nod_formula') => $node->formula,
            Config::get('csv.identifiers.node.dyn_nod_answer_type_id') => $node->answer_type_id,
            Config::get('csv.identifiers.node.dyn_nod_algorithm_id') => $node->algorithm_id,
            Config::get('csv.identifiers.node.dyn_nod_created_at') => $node->created_at,
            Config::get('csv.identifiers.node.dyn_nod_updated_at') => $node->updated_at,
            Config::get('csv.identifiers.node.dyn_nod_is_identifiable') => $node->is_identifiable,
            Config::get('csv.identifiers.node.dyn_nod_display_format') => $node->display_format,
        ];
    }

    /**
     * @param  $version
     * @return Collection version data
     */
    protected static function getVersionData($version)
    {
        return [
            Config::get('csv.identifiers.version.dyn_ver_id') => $version->id,
            Config::get('csv.identifiers.version.dyn_ver_medal_c_id') => $version->medal_c_id,
            Config::get('csv.identifiers.version.dyn_ver_name') => $version->name,
            Config::get('csv.identifiers.version.dyn_ver_algorithm_id') => $version->algorithm_id,
            Config::get('csv.identifiers.version.dyn_ver_created_at') => $version->created_at,
            Config::get('csv.identifiers.version.dyn_ver_updated_at') => $version->updated_at,
            Config::get('csv.identifiers.version.dyn_ver_consent_management') => $version->consent_management,
            Config::get('csv.identifiers.version.dyn_ver_study') => $version->study,
            Config::get('csv.identifiers.version.dyn_ver_is_arm_control') => $version->is_arm_control,
        ];
    }

    /**
     * @param  $algorithm
     * @return Collection algorithm data
     */
    protected static function getAlgorithmData($algorithm)
    {
        return [
            Config::get('csv.identifiers.algorithm.dyn_alg_id') => $algorithm->id,
            Config::get('csv.identifiers.algorithm.dyn_alg_medal_c_id') => $algorithm->medal_c_id,
            Config::get('csv.identifiers.algorithm.dyn_alg_name') => $algorithm->name,
            Config::get('csv.identifiers.algorithm.dyn_alg_created_at') => $algorithm->created_at,
            Config::get('csv.identifiers.algorithm.dyn_alg_updated_at') => $algorithm->updated_at,
        ];
    }

    /**
     * @param  $activity
     * @return Collection activity data
     */
    protected static function getActivityData($activity)
    {
        return [
            Config::get('csv.identifiers.activity.dyn_act_id') => $activity->id,
            Config::get('csv.identifiers.activity.dyn_act_medical_case_id') => $activity->medical_case_id,
            Config::get('csv.identifiers.activity.dyn_act_medal_c_id') => $activity->medal_c_id,
            Config::get('csv.identifiers.activity.dyn_act_step') => $activity->step,
            Config::get('csv.identifiers.activity.dyn_act_clinician') => $activity->clinician,
            Config::get('csv.identifiers.activity.dyn_act_mac_address') => $activity->mac_address,
            Config::get('csv.identifiers.activity.dyn_act_created_at') => $activity->created_at,
            Config::get('csv.identifiers.activity.dyn_act_updated_at') => $activity->updated_at,
        ];
    }

    /**
     * @param  $diagnosis
     * @return Collection diagnosis data
     */
    protected static function getDiagnosisData($diagnosis)
    {
        return [
            Config::get('csv.identifiers.diagnosis.dyn_dia_id') => $diagnosis->id,
            Config::get('csv.identifiers.diagnosis.dyn_dia_medal_c_id') => $diagnosis->medal_c_id,
            Config::get('csv.identifiers.diagnosis.dyn_dia_label') => $diagnosis->label,
            Config::get('csv.identifiers.diagnosis.dyn_dia_diagnostic_id') => $diagnosis->diagnostic_id,
            Config::get('csv.identifiers.diagnosis.dyn_dia_created_at') => $diagnosis->created_at,
            Config::get('csv.identifiers.diagnosis.dyn_dia_updated_at') => $diagnosis->updated_at,
            Config::get('csv.identifiers.diagnosis.dyn_dia_type') => $diagnosis->type,
            Config::get('csv.identifiers.diagnosis.dyn_dia_version_id') => $diagnosis->version_id,
        ];
    }

    /**
     * @param  $custom_diagnosis
     * @return Collection custom diagnosis data
     */
    protected static function getCustomDiagnosisData($custom_diagnosis)
    {
        return [
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_id') => $custom_diagnosis->id,
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_label') => $custom_diagnosis->label,
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_drugs') => $custom_diagnosis->drugs,
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_created_at') => $custom_diagnosis->created_at,
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_updated_at') => $custom_diagnosis->updated_at,
            Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_medical_case_id') => $custom_diagnosis->medical_case_id,
        ];
    }

    /**
     * @param  $diagnosis_reference
     * @return Collection diagnosis reference data
     */
    protected static function getDiagnosisReferenceData($diagnosis_reference)
    {
        return [
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_id') => $diagnosis_reference->id,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_agreed') => $diagnosis_reference->agreed,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_additional') => $diagnosis_reference->additional,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_diagnosis_id') => $diagnosis_reference->diagnosis_id,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_medical_case_id') => $diagnosis_reference->medical_case_id,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_created_at') => $diagnosis_reference->created_at,
            Config::get('csv.identifiers.diagnosis_reference.dyn_dre_updated_at') => $diagnosis_reference->updated_at,
        ];
    }

    /**
     * @param  $drug
     * @return Collection drug data
     */
    protected static function getDrugData($drug)
    {
        return [
            Config::get('csv.identifiers.drug.dyn_dru_id') => $drug->id,
            Config::get('csv.identifiers.drug.dyn_dru_medal_c_id') => $drug->medal_c_id,
            Config::get('csv.identifiers.drug.dyn_dru_type') => $drug->type,
            Config::get('csv.identifiers.drug.dyn_dru_label') => $drug->label,
            Config::get('csv.identifiers.drug.dyn_dru_description') => $drug->description,
            Config::get('csv.identifiers.drug.dyn_dru_diagnosis_id') => $drug->diagnosis_id,
            Config::get('csv.identifiers.drug.dyn_dru_created_at') => $drug->created_at,
            Config::get('csv.identifiers.drug.dyn_dru_updated_at') => $drug->updated_at,
            Config::get('csv.identifiers.drug.dyn_dru_is_anti_malarial') => $drug->is_anti_malarial,
            Config::get('csv.identifiers.drug.dyn_dru_is_antibiotic') => $drug->is_antibiotic,
            Config::get('csv.identifiers.drug.dyn_dru_duration') => $drug->duration,
        ];
    }

    /**
     * @param  $additional_drug
     * @return Collection additional drug data
     */
    protected static function getAdditionalDrugData($additional_drug)
    {
        return [
            Config::get('csv.identifiers.additional_drug.dyn_adr_id') => $additional_drug->id,
            Config::get('csv.identifiers.additional_drug.dyn_adr_drug_id') => $additional_drug->drug_id,
            Config::get('csv.identifiers.additional_drug.dyn_adr_medical_case_id') => $additional_drug->medical_case_id,
            Config::get('csv.identifiers.additional_drug.dyn_adr_formulationSelected') => $additional_drug->formulationSelected,
            Config::get('csv.identifiers.additional_drug.dyn_adr_agreed') => $additional_drug->agreed,
            Config::get('csv.identifiers.additional_drug.dyn_adr_version_id') => $additional_drug->version_id,
            Config::get('csv.identifiers.additional_drug.dyn_adr_created_at') => $additional_drug->created_at,
            Config::get('csv.identifiers.additional_drug.dyn_adr_updated_at') => $additional_drug->updated_at,
        ];
    }

    /**
     * @param  $drug_reference
     * @return Collection drug reference data
     */
    protected static function getDrugReferenceData($drug_reference)
    {
        return [
            Config::get('csv.identifiers.drug_reference.dyn_dre_id') => $drug_reference->id,
            Config::get('csv.identifiers.drug_reference.dyn_dre_drug_id') => $drug_reference->drug_id,
            Config::get('csv.identifiers.drug_reference.dyn_dre_diagnosis_id') => $drug_reference->diagnosis_id,
            Config::get('csv.identifiers.drug_reference.dyn_dre_agreed') => $drug_reference->agreed,
            Config::get('csv.identifiers.drug_reference.dyn_dre_created_at') => $drug_reference->created_at,
            Config::get('csv.identifiers.drug_reference.dyn_dre_updated_at') => $drug_reference->updated_at,
            Config::get('csv.identifiers.drug_reference.dyn_dre_formulationSelected') => $drug_reference->formulationSelected,
            Config::get('csv.identifiers.drug_reference.dyn_dre_formulation_id') => $drug_reference->formulation_id,
            Config::get('csv.identifiers.drug_reference.dyn_dre_additional') => $drug_reference->additional,
            Config::get('csv.identifiers.drug_reference.dyn_dre_duration') => $drug_reference->duration,
        ];
    }

    /**
     * @param  $management
     * @return Collection management data
     */
    protected static function getManagementData($management)
    {
        return [
            Config::get('csv.identifiers.management.dyn_man_id') => $management->id,
            Config::get('csv.identifiers.management.dyn_man_drug_id') => $management->drug_id,
            Config::get('csv.identifiers.management.dyn_man_diagnosis_id') => $management->diagnosis_id,
            Config::get('csv.identifiers.management.dyn_man_agreed') => $management->agreed,
            Config::get('csv.identifiers.management.dyn_man_created_at') => $management->created_at,
            Config::get('csv.identifiers.management.dyn_man_updated_at') => $management->updated_at,
            Config::get('csv.identifiers.management.dyn_man_formulationSelected') => $management->formulationSelected,
            Config::get('csv.identifiers.management.dyn_man_formulation_id') => $management->formulation_id,
            Config::get('csv.identifiers.management.dyn_man_additional') => $management->additional,
            Config::get('csv.identifiers.management.dyn_man_duration') => $management->duration,
        ];
    }

    /**
     * @param  $management_reference
     * @return Collection management reference data
     */
    protected static function getManagementReferenceData($management_reference)
    {
        return [
            Config::get('csv.identifiers.management_reference.dyn_mre_id') => $management_reference->id,
            Config::get('csv.identifiers.management_reference.dyn_mre_agreed') => $management_reference->agreed,
            Config::get('csv.identifiers.management_reference.dyn_mre_diagnosis_id') => $management_reference->diagnosis_id,
            Config::get('csv.identifiers.management_reference.dyn_mre_created_at') => $management_reference->created_at,
            Config::get('csv.identifiers.management_reference.dyn_mre_updated_at') => $management_reference->updated_at,
            Config::get('csv.identifiers.management_reference.dyn_mre_management_id') => $management_reference->management_id,
        ];
    }

    /**
     * @param  $answer_type
     * @return Collection answer type data
     */
    protected static function getAnswerTypeData($answer_type)
    {
        return [
            Config::get('csv.identifiers.answer_type.dyn_aty_id') => $answer_type->id,
            Config::get('csv.identifiers.answer_type.dyn_aty_value') => $answer_type->value,
            Config::get('csv.identifiers.answer_type.dyn_aty_created_at') => $answer_type->created_at,
            Config::get('csv.identifiers.answer_type.dyn_aty_updated_at') => $answer_type->updated_at,
        ];
    }

    /**
     * @param  $formulation
     * @return Collection formulation data
     */
    protected static function getFormulationData($formulation)
    {
        return [
            Config::get('csv.identifiers.formulation.dyn_for_id') => $formulation->id,
            Config::get('csv.identifiers.formulation.dyn_for_medical_form') => $formulation->medical_form,
            Config::get('csv.identifiers.formulation.dyn_for_administration_route_name') => $formulation->administration_route_name,
            Config::get('csv.identifiers.formulation.dyn_for_liquid_concentration') => $formulation->liquid_concentration,
            Config::get('csv.identifiers.formulation.dyn_for_dose_form') => $formulation->dose_form,
            Config::get('csv.identifiers.formulation.dyn_for_unique_dose') => $formulation->unique_dose,
            Config::get('csv.identifiers.formulation.dyn_for_by_age') => $formulation->by_age,
            Config::get('csv.identifiers.formulation.dyn_for_minimal_dose_per_kg') => $formulation->minimal_dose_per_kg,
            Config::get('csv.identifiers.formulation.dyn_for_maximal_dose_per_kg') => $formulation->maximal_dose_per_kg,
            Config::get('csv.identifiers.formulation.dyn_for_maximal_dose') => $formulation->maximal_dose,
            Config::get('csv.identifiers.formulation.dyn_for_description') => $formulation->description,
            Config::get('csv.identifiers.formulation.dyn_for_doses_per_day') => $formulation->doses_per_day,
            Config::get('csv.identifiers.formulation.dyn_for_created_at') => $formulation->created_at,
            Config::get('csv.identifiers.formulation.dyn_for_updated_at') => $formulation->updated_at,
            Config::get('csv.identifiers.formulation.dyn_for_drug_id') => $formulation->drug_id,
            Config::get('csv.identifiers.formulation.dyn_for_administration_route_category') => $formulation->administration_route_category,
            Config::get('csv.identifiers.formulation.dyn_for_medal_c_id') => $formulation->medal_c_id,
        ];
    }

    /**
     * @param  $answer
     * @return Collection answer data
     */
    protected static function getAnswerData($answer, $is_identifiable)
    {
        return [
            Config::get('csv.identifiers.answer.dyn_ans_id') => $answer->id,
            Config::get('csv.identifiers.answer.dyn_ans_label') => $answer->label,
            Config::get('csv.identifiers.answer.dyn_ans_medal_c_id') => $answer->medal_c_id,
            Config::get('csv.identifiers.answer.dyn_ans_node_id') => $answer->node_id,
            Config::get('csv.identifiers.answer.dyn_ans_created_at') => $answer->created_at,
            Config::get('csv.identifiers.answer.dyn_ans_updated_at') => $answer->updated_at,
        ];
    }

    /**
     *  Adds a patient to the data array
     */
    protected function addPatientData(&$data, $patient)
    {
        $data[$patient->id] = self::getPatientData($patient);
    }

    /**
     * Adds a medical case to the data array
     */
    protected function addMedicalCaseData(&$data, $medical_case)
    {
        $data[$medical_case->id] = self::getMedicalCaseData($medical_case);
    }

    /**
     * Adds a medical case answer to the data array
     */
    protected function addMedicalCaseAnswerData(&$data, $medical_case_answer)
    {
        $data[$medical_case_answer->id] = self::getMedicalCaseAnswerData($medical_case_answer);
    }

    /**
     * Adds a node to the data array
     */
    protected function addNodeData(&$data, $node)
    {
        $data[$node->id] = self::getNodeData($node);
    }

    /**
     * Adds a version to the data array
     */
    protected function addVersionData(&$data, $version)
    {
        $data[$version->id] = self::getVersionData($version);
    }

    /**
     * Adds an algorithm to the data array
     */
    protected function addAlgorithmData(&$data, $algorithm)
    {
        $data[$algorithm->id] = self::getAlgorithmData($algorithm);
    }

    /**
     * Adds an activity to the data array
     */
    protected function addActivityData(&$data, $activity)
    {
        $data[$activity->id] = self::getActivityData($activity);
    }

    /**
     * Adds a diagnosis to the data array
     */
    protected function addDiagnosisData(&$data, $diagnosis)
    {
        $data[$diagnosis->id] = self::getDiagnosisData($diagnosis);
    }

    /**
     * Adds a custom diagnosis to the data array
     */
    protected function addCustomDiagnosisData(&$data, $custom_diagnosis)
    {
        $data[$custom_diagnosis->id] = self::getCustomDiagnosisData($custom_diagnosis);
    }

    /**
     * Adds a diagnosis reference to the data array
     */
    protected function addDiagnosisReferenceData(&$data, $diagnosis_reference)
    {
        $data[$diagnosis_reference->id] = self::getDiagnosisReferenceData($diagnosis_reference);
    }

    /**
     * Adds a drug to the data array
     */
    protected function addDrugData(&$data, $drug)
    {
        $data[$drug->id] = self::getDrugData($drug);
    }

    /**
     * Adds an additional drug to the data array
     */
    protected function addAdditionalDrugData(&$data, $additional_drug)
    {
        $data[$additional_drug->id] = self::getAdditionalDrugData($additional_drug);
    }

    /**
     * Adds a drug reference to the data array
     */
    protected function addDrugReferenceData(&$data, $drug_reference)
    {
        $data[$drug_reference->id] = self::getDrugReferenceData($drug_reference);
    }

    /**
     * Adds a management to the data array
     */
    protected function addManagementData(&$data, $management)
    {
        $data[$management->id] = self::getManagementData($management);
    }

    /**
     * Adds a management reference to the data array
     */
    protected function addManagementReferenceData(&$data, $management_reference)
    {
        $data[$management_reference->id] = self::getManagementReferenceData($management_reference);
    }

    /**
     * Adds an answer type to the data array
     */
    protected function addAnswerTypeData(&$data, $answer_type)
    {
        $data[$answer_type->id] = self::getAnswerTypeData($answer_type);
    }

    /**
     * Adds a formulation to the data array
     */
    protected function addFormulationData(&$data, $formulation)
    {
        $data[$formulation->id] = self::getFormulationData($formulation);
    }

    /**
     *
     */
    protected function addAnswerData(&$data, $answer, $is_identifiable)
    {
        $data[$answer->id] = self::getAnswerData($answer, $is_identifiable);
    }

    protected static function isSkippedMedicalCaseAnswer($medical_case_answer)
    {
        return ($medical_case_answer->node->category == "background_calculation" && $medical_case_answer->node->display_format != 'Reference')
            || ($medical_case_answer->value == '' and $medical_case_answer->answer_id === null);
    }

    protected static function isSkippedDiagnosisReference($diagnosis_reference)
    {
        return $diagnosis_reference->excluded;
    }

    /**
     * Retrieve all the data.
     */
    protected function getDataFromMedicalCases()
    {
        // Initialize data arrays.
        $patients_data = [];
        $medical_cases_data = [];
        $medical_case_answers_data = [];
        $nodes_data = [];
        $versions_data = [];
        $algorithms_data = [];
        $activities_data = [];
        $diagnoses_data = [];
        $custom_diagnoses_data = [];
        $diagnosis_references_data = [];
        $drugs_data = [];
        $additional_drugs_data = [];
        $drug_references_data = [];
        $managements_data = [];
        $management_references_data = [];
        $answer_types_data = [];
        $formulations_data = [];
        $answers_data = [];

        if ($this->chunk_key == 1) {
            $patients_data[] = $this->getAttributeList(Config::get('csv.identifiers.patient'));
            $medical_cases_data[] = $this->getAttributeList(Config::get('csv.identifiers.medical_case'));
            $medical_case_answers_data[] = $this->getAttributeList(Config::get('csv.identifiers.medical_case_answer'));
            $nodes_data[] = $this->getAttributeList(Config::get('csv.identifiers.node'));
            $versions_data[] = $this->getAttributeList(Config::get('csv.identifiers.version'));
            $algorithms_data[] = $this->getAttributeList(Config::get('csv.identifiers.algorithm'));
            $activities_data[] = $this->getAttributeList(Config::get('csv.identifiers.activity'));
            $diagnoses_data[] = $this->getAttributeList(Config::get('csv.identifiers.diagnosis'));
            $custom_diagnoses_data[] = $this->getAttributeList(Config::get('csv.identifiers.custom_diagnosis'));
            $diagnosis_references_data[] = $this->getAttributeList(Config::get('csv.identifiers.diagnosis_reference'));
            $drugs_data[] = $this->getAttributeList(Config::get('csv.identifiers.drug'));
            $additional_drugs_data[] = $this->getAttributeList(Config::get('csv.identifiers.additional_drug'));
            $drug_references_data[] = $this->getAttributeList(Config::get('csv.identifiers.drug_reference'));
            $managements_data[] = $this->getAttributeList(Config::get('csv.identifiers.management'));
            $management_references_data[] = $this->getAttributeList(Config::get('csv.identifiers.management_reference'));
            $answer_types_data[] = $this->getAttributeList(Config::get('csv.identifiers.answer_type'));
            $formulations_data[] = $this->getAttributeList(Config::get('csv.identifiers.formulation'));
            $answers_data[] = $this->getAttributeList(Config::get('csv.identifiers.answer'));
        }

        foreach ($this->medical_cases as $medical_case) {
            $patient = $medical_case->patient;
            // get patients
            $this->addPatientData($patients_data, $patient);

            // get medical cases
            $this->addMedicalCaseData($medical_cases_data, $medical_case);

            $medical_case_answers = $medical_case->medical_case_answers;
            foreach ($medical_case_answers as $medical_case_answer) {
                if (self::isSkippedMedicalCaseAnswer($medical_case_answer)) {
                    continue;
                }
                // get medical_case_answers
                $this->addMedicalCaseAnswerData($medical_case_answers_data, $medical_case_answer);

                // get nodes
                $node = $medical_case_answer->node;
                $this->addNodeData($nodes_data, $node);

                // get answers
                $answers = $node->answers;
                $is_identifiable = $node->is_identifiable;
                foreach ($answers as $answer) {
                    $this->addAnswerData($answers_data, $answer, $is_identifiable);
                }
            }

            $version = $medical_case->version;
            // get versions
            $this->addVersionData($versions_data, $version);

            $algorithm = $version->algorithm;
            // get algorithms
            $this->addAlgorithmData($algorithms_data, $algorithm);

            $activities = $medical_case->activities;
            foreach ($activities as $activity) {
                // get activities
                $this->addActivityData($activities_data, $activity);
            }

            $diagnosis_references = $medical_case->diagnoses_references;
            foreach ($diagnosis_references as $diagnosis_reference) {
                if (self::isSkippedDiagnosisReference($diagnosis_reference)) {
                    continue;
                }

                $diagnosis = $diagnosis_reference->diagnoses;
                // get diagnosis references
                $this->addDiagnosisReferenceData($diagnosis_references_data, $diagnosis_reference);
                // get diagnoses
                $this->addDiagnosisData($diagnoses_data, $diagnosis);

                $drug_references = $diagnosis_reference->drug_references;
                foreach ($drug_references as $drug_reference) {
                    $drug = $drug_reference->drugs;
                    // get drug references
                    $this->addDrugReferenceData($drug_references_data, $drug_reference);
                    // get drugs
                    $this->addDrugData($drugs_data, $drug);

                    $formulations = $drug->formulations;
                    foreach ($formulations as $formulation) {
                        // get formulations
                        $this->addFormulationData($formulations_data, $formulation);
                    }

                    $additional_drugs = $drug->additional_drugs;
                    foreach ($additional_drugs as $additional_drug) {
                        // get additional drug
                        $this->addAdditionalDrugData($additional_drugs_data, $additional_drug);
                    }
                }

                $management_references = $diagnosis_reference->management_references;
                foreach ($management_references as $management_reference) {
                    $management = $management_reference->managements;
                    // get management references
                    $this->addManagementReferenceData($management_references_data, $management_reference);
                    // get managements
                    $this->addManagementData($managements_data, $management);
                }
            }

            $custom_diagnoses = $medical_case->custom_diagnoses;
            foreach ($custom_diagnoses as $custom_diagnosis) {
                // get custom diagnoses
                $this->AddCustomDiagnosisData($custom_diagnoses_data, $custom_diagnosis);
            }
        }

        $answer_types = Cache::store('array')->rememberForever('answer_types', function () {
            return AnswerType::all();
        });

        foreach ($answer_types as $answer_type) {
            // get answer type
            $this->addAnswerTypeData($answer_types_data, $answer_type);
        }

        return [
            Config::get('csv.file_names.patients') => $patients_data,
            Config::get('csv.file_names.medical_cases') => $medical_cases_data,
            Config::get('csv.file_names.medical_case_answers') => $medical_case_answers_data,
            Config::get('csv.file_names.nodes') => $nodes_data,
            Config::get('csv.file_names.versions') => $versions_data,
            Config::get('csv.file_names.algorithms') => $algorithms_data,
            Config::get('csv.file_names.activities') => $activities_data,
            Config::get('csv.file_names.diagnoses') => $diagnoses_data,
            Config::get('csv.file_names.custom_diagnoses') => $custom_diagnoses_data,
            Config::get('csv.file_names.diagnosis_references') => $diagnosis_references_data,
            Config::get('csv.file_names.drugs') => $drugs_data,
            Config::get('csv.file_names.additional_drugs') => $additional_drugs_data,
            Config::get('csv.file_names.drug_references') => $drug_references_data,
            Config::get('csv.file_names.managements') => $managements_data,
            Config::get('csv.file_names.management_references') => $management_references_data,
            Config::get('csv.file_names.answer_types') => $answer_types_data,
            Config::get('csv.file_names.formulations') => $formulations_data,
            Config::get('csv.file_names.answers') => $answers_data,
        ];
    }

    public function export()
    {
        $data = $this->getDataFromMedicalCases();
        $file_names = array_keys($data);

        $folder = public_path(Config::get('csv.folder_separated'));
        if (!File::exists($folder)) {
            File::makeDirectory($folder);
        }
        foreach ($file_names as $file_name) {
            $file = fopen($folder . $file_name, "a+");
            foreach ($data[$file_name] as $line) {
                $attributes = $this->attributesToStr((array) $line);
                fputcsv($file, $attributes);
            }
        }
        fclose($file);
    }
}
