<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Patient;
use App\MedicalCase;

class DataSheet implements WithMultipleSheets
{

    public function sheets():array
    {
      $sheets = [];
        $sheets[]= new PatientExport;
        $sheets[]= new Medical_CaseExport;
        $sheets[]=new MedicalCaseAnswerExport;
        $sheets[]=new AnswerExport;
        $sheets[]= new DiagnosisExport;
        $sheets[]= new DrugExport;
        $sheets[]=new ManagementExport;
        $sheets[]= new NodeExport;
        $sheets[]=new VersionExport;
      return $sheets;
    }
}
