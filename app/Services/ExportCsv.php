<?php

namespace App\Services;

use \InvalidArgumentException;
use Carbon\Carbon;

abstract class ExportCsv extends ExportService
{

    protected $from_date;
    protected $to_date;

    /**
     * Checks if the dates form a valid date interval.
     * @param from_date the start date
     * @param to_date the end date
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

    protected function getPatientList()
	{
		$data = [];

        $data[] = $this->getAttributeList(Config::get('csv.identifiers.patient'));
            foreach ($patients as $patient) {
                if($this->isSkippedPatient($patient)){
                    continue;
                }

                $data[] = [
                    Config::get('csv.identifiers.patient.dyn_pat_study_id_patient') => $patient->id,
                    Config::get('csv.identifiers.patient.dyn_pat_first_name')		=> $patient->first_name,
                    Config::get('csv.identifiers.patient.dyn_pat_last_name')        => $patient->last_name,
                    Config::get('csv.identifiers.patient.dyn_pat_created_at')       => $patient->created_at,
                    Config::get('csv.identifiers.patient.dyn_pat_updated_at')       => $patient->updated_at,
                    Config::get('csv.identifiers.patient.dyn_pat_birth_date')       => $patient->birthdate,
                    Config::get('csv.identifiers.patient.dyn_pat_gender') 			=> $patient->gender,
                    Config::get('csv.identifiers.patient.dyn_pat_local_patient_id') => $patient->local_patient_id,
                    Config::get('csv.identifiers.patient.dyn_pat_group_id') 		=> $patient->group_id,
                    Config::get('csv.identifiers.patient.dyn_pat_consent') 			=> $patient->consent,
                    Config::get('csv.identifiers.patient.dyn_pat_redcap') 			=> $patient->redcap,
                    Config::get('csv.identifiers.patient.dyn_pat_duplicate') 		=> $patient->duplicate,
                    Config::get('csv.identifiers.patient.dyn_pat_other_uid') 		=> $patient->other_uid,
                    Config::get('csv.identifiers.patient.dyn_pat_other_study_id') 	=> $patient->other_study_id,
                    Config::get('csv.identifiers.patient.dyn_pat_other_group_id') 	=> $patient->other_group_id,
                    Config::get('csv.identifiers.patient.dyn_pat_merged_with') 		=> $patient->merged_with,
                    Config::get('csv.identifiers.patient.dyn_pat_merged') 			=> $patient->merged,
                    Config::get('csv.identifiers.patient.dyn_pat_status') 			=> $patient->status,
                    Config::get('csv.identifiers.patient.dyn_pat_related_ids') 		=> $patient->related_ids,
                    Config::get('csv.identifiers.patient.dyn_pat_middle_name') 		=> $patient->middle_name,
                    Config::get('csv.identifiers.patient.dyn_pat_other_id') 		=> $patient->other_id
                ];
            }


        return $data;
	}
}