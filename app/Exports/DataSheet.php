<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataSheet implements WithMultipleSheets
{

    public function sheets():array
    {
      $sheets = [];
        $sheets['Patients']= new PatientExport;
        $sheets['Medical Cases']= new MedicalCaseExport;
        $sheets['Medical Case Answers']=new MedicalCaseAnswerExport;
        $sheets['Diagnoses']= new DiagnosisExport;
        $sheets['Drugs']= new DrugExport;
        $sheets['Managements']=new ManagementExport;
        $sheets['Nodes']= new NodeExport;
        $sheets['Versions']=new VersionExport;
      return $sheets;
    }
}
