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
                Config::get('csv.identifiers.patient.dyn_pat_last_name') => $patient->last_name
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
     * Write data to file.
     */
    private function writeToFile($file_name, $data)
    {
        $file = fopen($file_name, "w");
        foreach ($data as $line){
            fputcsv($file, (array) $line);
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