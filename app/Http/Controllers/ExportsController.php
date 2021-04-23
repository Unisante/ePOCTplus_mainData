<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PatientExport;
use App\Exports\DataSheet;
use App\Exports\DiagnosisReferenceExport;
use App\Exports\AnswerExport;
use App\Exports\Medical_CaseExport;
use App\Exports\MedicalCaseAnswerExport;
use App\Exports\DrugReferenceExport;
use App\Exports\AlgorithmExport;
use App\Exports\AdditionalDrugExport;
use App\Exports\CustomDiagnosisExport;
use App\Exports\ManagementReferenceExport;
use App\Exports\DrugExport;
use App\Exports\DiagnosisExport;
use App\Exports\FormulationExport;
use App\Exports\ManagementExport;
use App\Exports\NodeExport;
use App\Exports\AnswerTypeExport;
use App\Exports\VersionExport;
use Excel;

class ExportsController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }
    public function Patients(){
      return Excel::download(new PatientExport,'patients.csv');
    }
    public function cases(){
      return Excel::download(new Medical_CaseExport,'medical_cases.csv');
    }
    public function casesAnswers(){
      ini_set('memory_limit', '4096M');
      return Excel::download(new MedicalCaseAnswerExport,'medical_case_answers.csv');
    }
    public function answers(){
      return Excel::download(new AnswerExport,'answers.csv');
    }
    public function diagnosisReferences(){
      return Excel::download(new DiagnosisReferenceExport,'diagnosis_references.csv');
    }
    public function customDiagnoses(){
      return Excel::download(new CustomDiagnosisExport,'custom_diagnoses.csv');
    }
    public function drugReferences(){
      return Excel::download(new DrugReferenceExport,'drug references.csv');
    }
    public function additionalDrugs(){
      return Excel::download(new AdditionalDrugExport,'additional_drugs.csv');
    }
    public function managementReferences(){
      return Excel::download(new ManagementReferenceExport,'management_references.csv');
    }


    public function diagnoses(){
      return Excel::download(new DiagnosisExport,'diagnoses.csv');
    }
    public function drugs(){
      return Excel::download(new DrugExport,'drugs.csv');
    }
    public function formulations(){
      return Excel::download(new FormulationExport,'formulations.csv');
    }
    public function managements(){
      return Excel::download(new ManagementExport,'managements.csv');
    }
    public function nodes(){
      return Excel::download(new NodeExport,'nodes.csv');
    }
    public function answer_types(){
      return Excel::download(new AnswerTypeExport,'answer_types.csv');
    }
    public function algorithms(){
      return Excel::download(new AlgorithmExport,'algorithms.csv');
    }
    public function algorithmVersions(){
      return Excel::download(new VersionExport,'algorithm_versions.csv');
    }
}
