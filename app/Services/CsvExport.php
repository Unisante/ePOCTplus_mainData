<?php


namespace App\Services;

use App\Patient;
use App\MedicalCase;
use App\Node;
use App\Version;
use App\Algorithm;
use App\Activity;
use App\Diagnosis;
use App\CustomDiagnosis;
use App\DiagnosisReference;
use App\Drug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CsvExport
{
	public function __construct(){}

	/**
	 * Given identifier, returns the list of attributes
	 */
	private function getAttributeList($identifier)
	{
		$attribute_names = [];
		foreach ($identifier as $attribute_name) {
			$attribute_names[] = $attribute_name;
		}

		return $attribute_names;
	}

	/**
	 * Given the list of patients, create a formatted array of patient attributes.
	 */
	private function getFormattedPatientList($patients)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.patient'));
		foreach ($patients as $patient) {
			$data[] = [
				Config::get('csv.identifiers.patient.dyn_pat_study_id_patient') => $patient->id,
				Config::get('csv.identifiers.patient.dyn_pat_first_name')				=> $patient->first_name,
				Config::get('csv.identifiers.patient.dyn_pat_last_name')        => $patient->last_name,
				Config::get('csv.identifiers.patient.dyn_pat_created_at')       => $patient->created_at,
				Config::get('csv.identifiers.patient.dyn_pat_updated_at')       => $patient->updated_at,
				Config::get('csv.identifiers.patient.dyn_pat_birth_date')       => $patient->birthdate,
				Config::get('csv.identifiers.patient.dyn_pat_gender') 					=> $patient->gender,
				Config::get('csv.identifiers.patient.dyn_pat_local_patient_id') => $patient->local_patient_id,
				Config::get('csv.identifiers.patient.dyn_pat_group_id') 				=> $patient->group_id,
				Config::get('csv.identifiers.patient.dyn_pat_consent') 					=> $patient->consent,
				Config::get('csv.identifiers.patient.dyn_pat_redcap') 					=> $patient->redcap,
				Config::get('csv.identifiers.patient.dyn_pat_duplicate') 				=> $patient->duplicate,
				Config::get('csv.identifiers.patient.dyn_pat_other_uid') 				=> $patient->other_uid,
				Config::get('csv.identifiers.patient.dyn_pat_other_study_id') 	=> $patient->other_study_id,
				Config::get('csv.identifiers.patient.dyn_pat_other_group_id') 	=> $patient->other_group_id,
				Config::get('csv.identifiers.patient.dyn_pat_merged_with') 			=> $patient->merged_with,
				Config::get('csv.identifiers.patient.dyn_pat_merged') 					=> $patient->merged,
				Config::get('csv.identifiers.patient.dyn_pat_status') 					=> $patient->status,
				Config::get('csv.identifiers.patient.dyn_pat_related_ids') 			=> $patient->related_ids,
				Config::get('csv.identifiers.patient.dyn_pat_middle_name') 			=> $patient->middle_name,
				Config::get('csv.identifiers.patient.dyn_pat_other_id') 				=> $patient->other_id
			];
		}

		return $data;
	}

	/**
	 * Given the list of medical cases, create a formatted array of medical case attributes.
	 */
	private function getFormattedMedicalCaseList($medical_cases)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.medical_case'));
		foreach ($medical_cases as $medical_case) {
			$data[] = [
				Config::get('csv.identifiers.medical_case.dyn_mc_id') 										=> $medical_case->id,
				Config::get('csv.identifiers.medical_case.dyn_mc_version_id') 						=> $medical_case->version_id,
				Config::get('csv.identifiers.medical_case.dyn_mc_patient_id') 						=> $medical_case->patient_id,
				Config::get('csv.identifiers.medical_case.dyn_mc_created_at') 						=> $medical_case->created_at,
				Config::get('csv.identifiers.medical_case.dyn_mc_updated_at') 						=> $medical_case->updated_at,
				Config::get('csv.identifiers.medical_case.dyn_mc_local_medical_case_id') 	=> $medical_case->local_medical_case_id,
				Config::get('csv.identifiers.medical_case.dyn_mc_consent') 								=> $medical_case->consent,
				Config::get('csv.identifiers.medical_case.dyn_mc_isEligible') 						=> $medical_case->isEligible,
				Config::get('csv.identifiers.medical_case.dyn_mc_group_id') 							=> $medical_case->group_id,
				Config::get('csv.identifiers.medical_case.dyn_mc_redcap') 								=> $medical_case->redcap,
				Config::get('csv.identifiers.medical_case.dyn_mc_consultation_date') 			=> $medical_case->consultation_date,
				Config::get('csv.identifiers.medical_case.dyn_mc_closedAt') 							=> $medical_case->closedAt,
				Config::get('csv.identifiers.medical_case.dyn_mc_force_close') 						=> $medical_case->force_close,
				Config::get('csv.identifiers.medical_case.dyn_mc_mc_redcap_flag') 				=> $medical_case->mc_redcap_flag
			];
		}

		return $data;
	}

	/**
	 * Given the list of medical case answers, create a formatted array of medical case answer attributes.
	 */
	private function getFormattedMedicalCaseAnswerList($medical_case_answers)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.medical_case_answer'));
		foreach ($medical_case_answers as $medical_case_answer) {
			$data[] = [
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_id') 							=> $medical_case_answer->id,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_medical_case_id') 	=> $medical_case_answer->medical_case_id,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_answer_id') 				=> $medical_case_answer->answer_id,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_node_id')					=> $medical_case_answer->node_id,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_value') 						=> $medical_case_answer->value,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_created_at') 			=> $medical_case_answer->created_at,
				Config::get('csv.identifiers.medical_case_answer.dyn_mca_updated_at') 			=> $medical_case_answer->updated_at,
			];
		}

		return $data;
	}

	/**
	 * Given the list of nodes, create a formatted array of node attributes.
	 */
	private function getFormattedNodeList($nodes)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.node'));
		foreach ($nodes as $node) {
			$data[] = [
				Config::get('csv.identifiers.node.dyn_nod_id') 							=> $node->id,
				Config::get('csv.identifiers.node.dyn_nod_medal_c_id') 			=> $node->medal_c_id,
				Config::get('csv.identifiers.node.dyn_nod_reference') 			=> $node->reference,
				Config::get('csv.identifiers.node.dyn_nod_label') 					=> $node->label,
				Config::get('csv.identifiers.node.dyn_nod_type') 						=> $node->type,
				Config::get('csv.identifiers.node.dyn_nod_category') 				=> $node->category,
				Config::get('csv.identifiers.node.dyn_nod_priority') 				=> $node->priority,
				Config::get('csv.identifiers.node.dyn_nod_stage') 					=> $node->stage,
				Config::get('csv.identifiers.node.dyn_nod_description') 		=> $node->description,
				Config::get('csv.identifiers.node.dyn_nod_formula') 				=> $node->formula,
				Config::get('csv.identifiers.node.dyn_nod_answer_type_id') 	=> $node->answer_type_id,
				Config::get('csv.identifiers.node.dyn_nod_algorithm_id') 		=> $node->algorithm_id,
				Config::get('csv.identifiers.node.dyn_nod_created_at') 			=> $node->created_at,
				Config::get('csv.identifiers.node.dyn_nod_updated_at') 			=> $node->updated_at,
				Config::get('csv.identifiers.node.dyn_nod_is_identifiable') => $node->is_identifiable,
				Config::get('csv.identifiers.node.dyn_nod_display_format') 	=> $node->display_format,
			];
		}

		return $data;
	}

	/**
	 * Given the list of versions, create a formatted array of version attributes,
	 */
	private function getFormattedVersionList($versions)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.version'));
		foreach ($versions as $version) {
			$data[] = [
				Config::get('csv.identifiers.version.dyn_ver_id') 								=> $version->id,
				Config::get('csv.identifiers.version.dyn_ver_medal_c_id') 				=> $version->medal_c_id,
				Config::get('csv.identifiers.version.dyn_ver_name') 							=> $version->name,
				Config::get('csv.identifiers.version.dyn_ver_algorithm_id') 			=> $version->algorithm_id,
				Config::get('csv.identifiers.version.dyn_ver_created_at') 				=> $version->created_at,
				Config::get('csv.identifiers.version.dyn_ver_updated_at') 				=> $version->updated_at,
				Config::get('csv.identifiers.version.dyn_ver_consent_management') => $version->consent_management,
				Config::get('csv.identifiers.version.dyn_ver_study') 							=> $version->study
			];
		}

		return $data;
	}

	/**
	 * Given the list of algorithms, create a formatted array of algorithm attributes.
	 */
	private function getFormattedAlgorithmList($algorithms)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.algorithm'));
		foreach ($algorithms as $algorithm) {
			$data[] = [
				Config::get('csv.identifiers.algorithm.dyn_alg_id') 						=> $algorithm->id,
				Config::get('csv.identifiers.algorithm.dyn_alg_medal_c_id') 		=> $algorithm->medal_c_id,
				Config::get('csv.identifiers.algorithm.dyn_alg_name') 					=> $algorithm->name,
				Config::get('csv.identifiers.algorithm.dyn_alg_created_at') 		=> $algorithm->created_at,
				Config::get('csv.identifiers.algorithm.dyn_alg_updated_at') 		=> $algorithm->updated_at,
				Config::get('csv.identifiers.algorithm.dyn_alg_is_arm_control') => $algorithm->is_arm_control,
			];
		}

		return $data;
	}

	/**
	 * Given the list of algorithms, create a formatted array of activity attributes.
	 */
	private function getFormattedActivityList($activities)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.activity'));
		foreach ($activities as $activity) {
			$data[] = [
				Config::get('csv.identifiers.activity.dyn_act_id') 							=> $activity->id,
				Config::get('csv.identifiers.activity.dyn_act_medical_case_id') => $activity->medical_case_id,
				Config::get('csv.identifiers.activity.dyn_act_medal_c_id') 			=> $activity->medal_c_id,
				Config::get('csv.identifiers.activity.dyn_act_step') 						=> $activity->step,
				Config::get('csv.identifiers.activity.dyn_act_clinician') 			=> $activity->clinician,
				Config::get('csv.identifiers.activity.dyn_act_mac_address') 		=> $activity->mac_address,
				Config::get('csv.identifiers.activity.dyn_act_created_at') 			=> $activity->created_at,
				Config::get('csv.identifiers.activity.dyn_act_updated_at') 			=> $activity->updated_at,
			];
		}

		return $data;
	}

	/**
	 * Given the list of diagnoses, create a formatted array of diagnosis attributes.
	 */
	private function getFormattedDiagnosisList($diagnoses)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.diagnosis'));
		foreach ($diagnoses as $diagnosis) {
			$data[] = [
				Config::get('csv.identifiers.diagnosis.dyn_dia_id') 						=> $diagnosis->id,
				Config::get('csv.identifiers.diagnosis.dyn_dia_medal_c_id') 		=> $diagnosis->medal_c_id,
				Config::get('csv.identifiers.diagnosis.dyn_dia_label')		 			=> $diagnosis->label,
				Config::get('csv.identifiers.diagnosis.dyn_dia_diagnostic_id') 	=> $diagnosis->diagnostic_id,
				Config::get('csv.identifiers.diagnosis.dyn_dia_created_at') 		=> $diagnosis->created_at,
				Config::get('csv.identifiers.diagnosis.dyn_dia_updated_at') 		=> $diagnosis->updated_at,
				Config::get('csv.identifiers.diagnosis.dyn_dia_type') 					=> $diagnosis->type,
				Config::get('csv.identifiers.diagnosis.dyn_dia_version_id') 		=> $diagnosis->version_id,
			];
		}

		return $data;
	}

	/**
	 * Given the list of custom diagnoses, create a formatted array of custom diagnosis attributes.
	 */
	private function getFormattedCustomDiagnosisList($custom_diagnoses)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.custom_diagnosis'));
		foreach ($custom_diagnoses as $custom_diagnosis) {
			$data[] = [
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_id') 							=> $custom_diagnosis->id,
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_label') 					=> $custom_diagnosis->label,
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_drugs') 					=> $custom_diagnosis->drugs,
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_created_at') 			=> $custom_diagnosis->created_at,
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_updated_at') 			=> $custom_diagnosis->updated_at,
				Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_medical_case_id') => $custom_diagnosis->medical_case_id
			];
		}

		return $data;
	}

	/**
	 * Given the list of diagnosis references, create a formatted array of diagnosis reference attributes.
	 */
	private function getFormattedDiagnosisReferenceList($diagnosis_references)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.diagnosis_reference'));
		foreach ($diagnosis_references as $diagnosis_reference) {
			$data[] = [
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_id') 							=> $diagnosis_reference->id,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_agreed') 					=> $diagnosis_reference->agreed,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_additional') 			=> $diagnosis_reference->additional,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_diagnosis_id') 		=> $diagnosis_reference->diagnosis_id,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_medical_case_id') 	=> $diagnosis_reference->medical_case_id,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_created_at') 			=> $diagnosis_reference->created_at,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_updated_at') 			=> $diagnosis_reference->updated_at,
				Config::get('csv.identifiers.diagnosis_reference.dyn_dre_excluded') 				=> $diagnosis_reference->excluded
			];
		}

		return $data;
	}

	/**
	 * Given the list of drugs, create a formatted array of drug attributes.
	 */
	private function getFormattedDrugList($drugs)
	{
		$data = [];
		$data[] = $this->getAttributeList(Config::get('csv.identifiers.drug'));
		foreach ($drugs as $drug) {
			$data[] = [
				Config::get('csv.identifiers.drug.dyn_dru_id') 								=> $drug->id,
				Config::get('csv.identifiers.drug.dyn_dru_medal_c_id') 				=> $drug->medal_c_id,
				Config::get('csv.identifiers.drug.dyn_dru_type') 							=> $drug->type,
				Config::get('csv.identifiers.drug.dyn_dru_label') 						=> $drug->label,
				Config::get('csv.identifiers.drug.dyn_dru_description') 			=> $drug->description,
				Config::get('csv.identifiers.drug.dyn_dru_diagnosis_id') 			=> $drug->diagnosis_id,
				Config::get('csv.identifiers.drug.dyn_dru_created_at') 				=> $drug->created_at,
				Config::get('csv.identifiers.drug.dyn_dru_updated_at') 				=> $drug->updated_at,
				Config::get('csv.identifiers.drug.dyn_dru_is_anti_malarial') 	=> $drug->is_anti_malarial,
				Config::get('csv.identifiers.drug.dyn_dru_is_antibiotic') 		=> $drug->is_antibiotic,
				Config::get('csv.identifiers.drug.dyn_dru_duration') 					=> $drug->duration
			];
		}

		return $data;
	}

	/**
	 * Retrieve the list of patient ids.
	 */
	private function getPatientIds($patients)
	{
		$patient_ids = [];
		foreach ($patients as $patient) {
			$patient_ids[] = $patient[Config::get('csv.identifiers.patient.dyn_pat_study_id_patient')];
		}

		return $patient_ids;
	}

	/**
	 * Retrieve the list of medcial case answer ids.
	 */
	private function getMedicalCaseIds($medical_cases)
	{
		$medical_case_ids = [];
		foreach ($medical_cases as $medical_case) {
			$medical_case_ids[] = $medical_case[Config::get('csv.identifiers.medical_case.dyn_mc_id')];
		}

		return $medical_case_ids;
	}

	/**
	 * Retrieve the list of patients.
	 */
	private function getPatientList($fromDate, $toDate)
	{
		// only take patients created in the given date interval.
		$patients = Patient::whereBetween(Config::get('csv.identifiers.patient.dyn_pat_created_at'), array($fromDate, $toDate));

		// discard patients with specific attributes.
		$patient_discarded_names = Config::get('csv.patient_discarded_names');
		foreach ($patient_discarded_names as $discarded_name) {
			$patients = $patients
				->where(Config::get('csv.identifiers.patient.dyn_pat_first_name'), 'NOT ILIKE', '%' . $discarded_name . '%')
				->where(Config::get('csv.identifiers.patient.dyn_pat_last_name'), 'NOT ILIKE', '%' . $discarded_name . '%');
		}

		return $patients->get();
	}

	/**
	 * Retrieve the list of medical cases.
	 */
	private function getMedicalCaseList($patient_ids)
	{
		$medical_cases = MedicalCase::whereIn(Config::get('csv.identifiers.medical_case.dyn_mc_patient_id'), $patient_ids);

		return $medical_cases->get();
	}

	/**
	 * Retrieve the list of medical case answers.
	 */
	private function getMedicalCaseAnswerList($medical_case_ids)
	{
		// various string values for the query.
		$medical_case_id = Config::get('csv.identifiers.medical_case_answer.dyn_mca_medical_case_id');
		$category_str = Config::get('csv.identifiers.node.dyn_nod_category');
		$display_format_str = Config::get('csv.identifiers.node.dyn_nod_display_format');

		// subquery to select nodes.
		$selected_nodes = DB::table('nodes')
			->where($category_str, '!=', 'background_calculation')
			->orWhere($display_format_str, '=', 'Reference');

		$medical_case_answers = DB::table('medical_case_answers')
			->whereIn($medical_case_id, $medical_case_ids)
			->joinSub($selected_nodes, 'selected_nodes', function ($join) {
				$medical_case_answers_node_id = 'medical_case_answers.' . Config::get('csv.identifiers.medical_case_answer.dyn_mca_node_id');
				$selected_nodes_id = 'selected_nodes.' . Config::get('csv.identifiers.node.dyn_nod_id');
				$join->on($medical_case_answers_node_id, '=', $selected_nodes_id);
			})
			->select('medical_case_answers.*');

		return $medical_case_answers->get();
	}

	/**
	 * Retrieve the list of nodes.
	 */
	private function getNodeList()
	{
		$nodes = Node::all();

		return $nodes;
	}

	/**
	 * Retrieve the list of versions.
	 */
	private function getVersionList()
	{
		$versions = Version::all();

		return $versions;
	}

	/**
	 * Retrieve the list of algorithms.
	 */
	private function getAlgorithmList()
	{
		$algorithms = Algorithm::all();

		return $algorithms;
	}

	/**
	 * Retrieve the list of activities.
	 */
	private function getActivityList($medical_case_ids)
	{
		$activities = Activity::whereIn(Config::get('csv.identifiers.activity.dyn_act_medical_case_id'), $medical_case_ids);

		return $activities->get();
	}

	/**
	 * Retrieve the list of activities.
	 */
	private function getDiagnosisList()
	{
		$diagnoses = Diagnosis::all();

		return $diagnoses;
	}

	/**
	 * Retrieve the list of custom diagnoses.
	 */
	private function getCustomDiagnosisList($medical_case_ids)
	{
		$custom_diagnoses = CustomDiagnosis::whereIn(Config::get('csv.identifiers.custom_diagnosis.dyn_cdi_medical_case_id'), $medical_case_ids);

		return $custom_diagnoses->get();
	}

	/**
	 * Retrieve the list of diagnosis references.
	 */
	private function getDiagnosisReferenceList($medical_case_ids)
	{
		$dianosis_references = DiagnosisReference::whereIn(Config::get('csv.identifiers.diagnosis_reference.dyn_dre_medical_case_id'), $medical_case_ids);

		return $dianosis_references->get();
	}

	private function getDrugList()
	{
		$drugs = Drug::all();

		return $drugs;
	}

	/**
	 * Returns a string representation of an array of attributes.
	 */
	private function attributesToStr($attributes)
	{
		$new_attributes = [];
		foreach ($attributes as $attribute) {
			if (is_array($attribute)) {
				$new_attributes[] = implode(',', $attribute);
			} else if (is_bool($attribute)) {
				$new_attributes[] = $attribute ? "1" : "0";
			} else {
				$new_attributes[] = $attribute;
			}
		}

		return $new_attributes;
	}

	/**
	 * Write data to file.
	 */
	private function writeToFile($file_name, $data)
	{
		$file = fopen($file_name, "w");
		foreach ($data as $line) {
			$attributes = $this->attributesToStr((array) $line);
			fputcsv($file, $attributes);
		}
		fclose($file);
	}

	/**
	 * Generate new zip file given files' names and download it.
	 */
	private static function downloadFiles($file_names)
	{
		$extract_file_name = Config::get('csv.public_extract_name');
		$file_from_public = base_path() . '/public/' . $extract_file_name;

		// generate the data file.
		$zipper = new \Madnest\Madzipper\Madzipper;
		$zipper->make($extract_file_name)->add($file_names);
		$zipper->close();

		// download the data file.
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=" . $file_from_public);
		header("Content-Type: application/csv; ");
		readfile($file_from_public);

		// delete the data files.
		foreach ($file_names as $csv) {
			unlink($csv);
		}
		unlink($file_from_public);
	}

	/**
	 * Export data created in a given date interval.
	 */
	public function exportDataByDate($fromDate, $toDate)
	{
		$file_names = [];
		$file_names[] = Config::get('csv.file_names.patients');
		$file_names[] = Config::get('csv.file_names.medical_cases');
		$file_names[] = Config::get('csv.file_names.medical_case_answers');
		$file_names[] = Config::get('csv.file_names.nodes');
		$file_names[] = Config::get('csv.file_names.versions');
		$file_names[] = Config::get('csv.file_names.algorithms');
		$file_names[] = Config::get('csv.file_names.activities');
		$file_names[] = Config::get('csv.file_names.diagnoses');
		$file_names[] = Config::get('csv.file_names.custom_diagnoses');
		$file_names[] = Config::get('csv.file_names.diagnosis_references');
		$file_names[] = Config::get('csv.file_names.drugs');

		// get patients data.
		$patients = $this->getPatientList($fromDate, $toDate);
		$patients_data = $this->getFormattedPatientList($patients);
		$patient_ids = $this->getPatientIds($patients);
		$this->writeToFile($file_names[0], $patients_data);

		// get medical cases data.
		$medical_cases = $this->getMedicalCaseList($patient_ids);
		$medical_cases_data = $this->getFormattedMedicalCaseList($medical_cases);
		$medical_case_ids = $this->getMedicalCaseIds($medical_cases);
		$this->writeToFile($file_names[1], $medical_cases_data);

		// get medical case answers data.
		$medical_case_answers = $this->getMedicalCaseAnswerList($medical_case_ids);
		$medical_case_answers_data = $this->getFormattedMedicalCaseAnswerList($medical_case_answers);
		$this->writeToFile($file_names[2], $medical_case_answers_data);

		// get nodes data.
		$nodes = $this->getNodeList();
		$nodes_data = $this->getFormattedNodeList($nodes);
		$this->writeToFile($file_names[3], $nodes_data);

		// get versions data. TODO (is_arm_control)
		$versions = $this->getVersionList();
		$versions_data = $this->getFormattedVersionList($versions);
		$this->writeToFile($file_names[4], $versions_data);

		// get algorithms data. TODO (is_arm_control)
		$algorithms = $this->getAlgorithmList();
		$algorithms_data = $this->getFormattedAlgorithmList($algorithms);
		$this->writeToFile($file_names[5], $algorithms_data);

		// get activities data.
		$activities = $this->getActivityList($medical_case_ids);
		$activities_data = $this->getFormattedActivityList($activities);
		$this->writeToFile($file_names[6], $activities_data);

		// get diagnoses data.
		$diagnoses = $this->getDiagnosisList();
		$diagnoses_data = $this->getFormattedDiagnosisList($diagnoses);
		$this->writeToFile($file_names[7], $diagnoses_data);

		// get custom diagnoses data.
		$custom_diagnoses = $this->getCustomDiagnosisList($medical_case_ids);
		$custom_diagnoses_data = $this->getFormattedCustomDiagnosisList($custom_diagnoses);
		$this->writeToFile($file_names[8], $custom_diagnoses_data);

		// get diagnosis references data.
		$diagnosis_references = $this->getDiagnosisReferenceList($medical_case_ids);
		$diagnosis_reference_data = $this->getFormattedDiagnosisReferenceList($diagnosis_references);
		$this->writeToFile($file_names[9], $diagnosis_reference_data);

		// get drugs data.
		$drugs = $this->getDrugList();
		$drugs_data = $this->getFormattedDrugList($drugs);
		$this->writeToFile($file_names[10], $drugs_data);

		// get additional drugs data.

		// get drug references data.

		// get managements data.

		// get management references data.

		// get answer types data.

		// get formulations data.

		$this->downloadFiles($file_names);
		exit();
	}
}
