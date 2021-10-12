<?php

namespace App\Services;

use \InvalidArgumentException;
use \DateInterval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Config;


abstract class ExportCsv extends ExportService
{

    protected $from_date;
    protected $to_date;

    /**
     * @return true return the default value if the user has permission to see sensitive data, otherwise remove it.
     */
    protected static function ValueWithPermission($value)
    {
        return Auth::user()->can('See_Sensitive_Data') ? $value : null;
    }

    protected static function AnswerValueWithPermission($value, $is_identifiable)
    {
        return (!$is_identifiable || Auth::user()->can('See_Sensitive_Data')) ? $value : null;
    }

    /**
     * Checks if the dates form a valid date interval.
     * @param DateTime from_date, the start date
     * @param DateTime to_date, the end date
     * @throws InvalidArgumentException
     */
    protected static function checkDateInterval($from_date, $to_date)
    {
        if($from_date === null || $to_date === null){
            throw new InvalidArgumentException("Date should not be null.");
        }

        if($from_date > $to_date){
            throw new InvalidArgumentException("Invalid date interval.");
        }

        if($to_date > Carbon::now()){
            throw new InvalidArgumentException("Date should not be in the future.");
        }
    }

    /**
     * @param MedicalCase medical_case, a medical case
     * @return boolean true if the medical case should be skipped, false otherwise
     */
    private function isSkippedMedicalCase($medical_case)
    {
        // select medical cases only in date interval.
        $date = $medical_case->patient->created_at;
        if($date < $this->from_date || $date > $this->to_date){
            return true;
        }

        // patient's name may discard a medical case.
        $patient_discarded_names = Config::get('csv.patient_discarded_names');
		foreach ($patient_discarded_names as $discarded_name) {
			$first_name = trim(strtolower($medical_case->patient->first_name));
			$last_name = trim(strtolower($medical_case->patient->last_name));

			if(str_contains($first_name, $discarded_name) || str_contains($last_name, $discarded_name)){
				return true;
			}
		}

        return false;
    }

    /**
     * @param MedicalCaseAnswer medical_case_answer, a medical case answer
     * @return boolean true if the medical case answer should be skipped, false otherwise
     */
    protected static function isSkippedMedicalCaseAnswer($medical_case_answer){
        return ($medical_case_answer->node->category == "background_calculation" && $medical_case_answer->node->display_format != 'Reference')
            || ($medical_case_answer->value == '' and $medical_case_answer->answer_id === null);
    }

    /**
     * @param DiagnosisReference diagnosis_reference, a diagnosis reference
     * @return boolean true if the diagnosis reference should be skipped, false otherwise
     */
    protected static function isSkippedDiagnosisReference($diagnosis_reference){
        return $diagnosis_reference->excluded;
    }

    /**
     * @param Collection medical_cases, the list of medical cases to filter
     * @return Collection filtered list of medical cases.
     */
    protected function getFilteredMedicalCases($medical_cases)
    {
        $new_medical_cases = [];
        foreach($medical_cases as $medical_case){
            if($this->isSkippedMedicalCase($medical_case)){
               continue; 
            }

            $new_medical_cases[] = $medical_case;
        }

        return $new_medical_cases;
    }

    /**
     * Constructor
     * @param Collection medical_cases, medical cases to export
     * @param DateTime from_date, the starting date
     * @param DateTime to_date, the ending date
     */
    public function __construct($medical_cases, $from_date, $to_date)
    {
        self::checkDateInterval($from_date, $to_date);

        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->to_date->add(new DateInterval('P1D'));

        parent::__construct($this->getFilteredMedicalCases($medical_cases));
    }

    /**
	 * Given identifier, returns the list of attributes
	 */
	protected function getAttributeList($identifier)
	{
		$attribute_names = [];
		foreach($identifier as $attribute_name) {
			$attribute_names[] = $attribute_name;
		}

		return $attribute_names;
	}

    /**
	 * Returns a string representation of an array of attributes.
	 */
	protected function attributesToStr($attributes)
	{
		$new_attributes = [];
		foreach ($attributes as $attribute) {
			if(is_array($attribute)) {
				$new_attributes[] = implode(',', $attribute);
			} else if(is_bool($attribute)) {
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
	protected function writeToFile($file_name, $data)
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
	protected function downloadFiles($file_names)
	{
		$extract_file_name = Config::get('csv.public_extract_name');
		$file_from_public = base_path() . '/public/' . $extract_file_name . '.zip';

		// generate the data file.
		$zipper = new \Madnest\Madzipper\Madzipper;
		$zipper->make($extract_file_name . '.zip')->add($file_names);
		$zipper->close();

		// download the data file.
        $from_date_str = $this->from_date->format('Y-m-d');
        $to_date_str = $this->to_date->sub(new DateInterval('P1D'))->format('Y-m-d');

		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=" . Config::get('csv.public_extract_name') . '_' . $from_date_str . '_' . $to_date_str . '.zip');
		header("Content-Type: application/csv; ");
		readfile($file_from_public);

		// delete the data files.
		foreach ($file_names as $csv) {
			unlink($csv);
		}
		unlink($file_from_public);
	}
}