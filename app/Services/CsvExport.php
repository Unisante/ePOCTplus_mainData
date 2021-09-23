<?php


namespace App\Services;

use App\Patient;
use App\PatientFollowUp;
use Illuminate\Support\Facades\Config;

class CsvExport
{
    public function __construct(){}

    /**
     * Given the list of patients, create a formatted array of patient attributes.
     */
    private function getFormattedPatientList($patients)
    {
        $data = [];
        // get attributes' names.
        $attributes = [];
        foreach(Config::get('csv.identifiers.patient') as $attribute){
            $attributes[] = $attribute;
        }
        $data[] = $attributes;

        // get data.
        foreach($patients as $patient){
            $data[] = [
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
                Config::get('csv.identifiers.patient.dyn_pat_other_id') => $patient->other_id
            ];
        }

        return $data;
    }

    /**
     * Retrieve the list of patients.
     */
    private function getPatientList($fromDate, $toDate)
    {
        // only take patients created in the given date interval.
        $patients = Patient::whereBetween('created_at', array($fromDate, $toDate));

        // discard patients with specific attributes.
        $patient_discarded_names = Config::get('csv.patient_discarded_names');
        foreach($patient_discarded_names as $discarded_name){
            $patients = $patients
                ->where('first_name', 'NOT ILIKE', '%' . $discarded_name . '%')
                ->where('last_name', 'NOT ILIKE', '%' . $discarded_name . '%');
        }

        return $patients->get();
    }

    /**
     * Returns a string representation of an array of attributes.
     */
    private function attributesToStr($attributes){
        $new_attributes = [];
        foreach($attributes as $attribute){
            if(is_array($attribute)){
                $new_attributes[] = implode(',', $attribute);
            }else{
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
        foreach ($data as $line){
            $attributes = self::attributesToStr((array) $line);
            fputcsv($file, $attributes);
        }
        fclose($file);
    }

    /**
     * Generate new zip file given files' names and download it.
     */
    private function downloadFiles($file_names)
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
        foreach($file_names as $csv){
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

        // get patients data.
        $patients = self::getPatientList($fromDate, $toDate);
        $patients_data = self::getFormattedPatientList($patients);
        self::writeToFile($file_names[0], $patients_data);

        self::downloadFiles($file_names);
        exit();
    }
}