<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class ExportCsvFlat extends ExportCsv
{
     /**
     * Constructor
     */
    public function __construct($medical_cases, $from_date, $to_date)
    {
        parent::__construct($medical_cases, $from_date, $to_date);
    }

    /**
     * Retrieve all the data.
     */
    protected function getDataFromMedicalCases()
    {
        $data = [];
        $data[] = [];

        foreach($this->medical_cases as $medical_case){
            $patient = $medical_case->patient;
            $medical_case_answers = $medical_case->medical_case_answers;

            $index = $patient->id . '-' . $medical_case->id;
            $data[$index] = [];
            $data[$index][Config::get('csv.flat.identifiers.answers.dyn_fla_patient_id')] = $patient->local_patient_id;
            $data[$index][Config::get('csv.flat.identifiers.answers.dyn_fla_medical_case_id')] = $medical_case->local_medical_case_id;
            
            foreach($medical_case_answers as $medical_case_answer){
                $node = $medical_case_answer->node;
                $answer = $medical_case_answer->answer;
                $label = $node === null ? null : $medical_case_answer->node->label;
                $answer = $answer === null ? null : $answer->label;
                $data[$index][$label] = $answer;
                $data[0][$label] = $label;
            }
        }

        $data[0] = array_unique($data[0]);
        array_unshift($data[0], Config::get('csv.flat.identifiers.answers.dyn_fla_medical_case_id'));
        array_unshift($data[0], Config::get('csv.flat.identifiers.answers.dyn_fla_patient_id'));

        return $data;
    }
    
    public function export()
    {
        $data = $this->getDataFromMedicalCases();

        foreach(Config::get('csv.flat.file_names') as $file_name){
            $this->writeToFile($file_name, $data);
        }

		$this->downloadFiles(Config::get('csv.flat.file_names'));
		exit();
    }
}