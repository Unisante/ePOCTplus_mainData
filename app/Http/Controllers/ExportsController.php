<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\MedicalCase;
use App\Drug;
use App\Formulation;
use App\DiagnosisReference;
use App\DrugReference;
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
use App\Patient;
use Illuminate\Support\Facades\DB;
use App\MedicalCaseAnswer;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use App\User;
use Auth;
use DateTime;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExportsController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }
    public function selectDate(){
      if(Patient::all()->count() == 0){
        return back()->withErrors("We Currently Do not have records in the database");
      }
      $data=array(
        'currentUser'=>Auth::user(),
        'userCount'=>User::all()->count(),
        'mdCases'=> MedicalCase::all()->count(),
        'oldest_date'=>MedicalCase::oldest()->first()->created_at->format('Y-m-d'),
        'newest_date'=>MedicalCase::latest()->first()->created_at->format('Y-m-d'),
        'patientCount'=> Patient::all()->count(),

      );
      // choose this by default date of the first medical case

      return view('exports.index')->with($data);;
    }
    public function exportFlatZip(Request $request){
      $caseAnswers=new MedicalCaseAnswer();
      $caseObject = new MedicalCase();
      $thingsArray=[$caseAnswers->makeFlatCsv()];
      $things_to_add=["nodes","medical_cases"];
      foreach($things_to_add as $table){
        array_push($thingsArray,$caseObject->getDataCsv($table,null,null));
      }
      $zipper = new \Madnest\Madzipper\Madzipper;
      $zipper->make("ibu.zip")->add($thingsArray);
      $zipper->close();
      $zipfileFromPublic= base_path().'/public/ibu.zip';
      // download
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=".$zipfileFromPublic);
      header("Content-Type: application/csv; ");
      readfile($zipfileFromPublic);
      // deleting file
      foreach($thingsArray as $csv){
       unlink($csv);
      }
      unlink($zipfileFromPublic);
      exit();
    }
    public function exportZipByDate(Request $request){
      ini_set('memory_limit', '4096M');
      ini_set('max_execution_time', '3600');
      $request->validate(array(
        'fromDate' => 'required|date',
        'toDate' => 'required|date',
      ));
      if(Patient::all()->count() == 0){
        return back()->withErrors("We Currently Do not have records in the database");
      }
      $fromDate= new DateTime($request->input('fromDate'));
      $toDate= new DateTime($request->input('toDate'));
      if($fromDate > Carbon::now() || $toDate > Carbon::now()){
        return back()->withErrors("Please Insert the right Date");
      }
      $patients=new Patient();
      $cases = new MedicalCase();
      $thingsArray=[];
      $things_to_add=["medical_cases","medical_case_answers","nodes","answers","algorithms","versions","answer_types","additional_drugs","custom_diagnoses","diagnoses","diagnosis_references","drugs","drug_references","formulations","managements","management_references"];
      foreach($things_to_add as $table){
        array_push($thingsArray,$cases->getDataCsv($table,$fromDate,$toDate));
      }

      $filename=$patients->patientData();
      $thingsArray = Arr::prepend($thingsArray, $filename);
      $zipper = new \Madnest\Madzipper\Madzipper;
      $zipper->make("ibu.zip")->add($thingsArray);
      $zipper->close();
      $fileFromPublic=base_path().'/public/ibu.zip';;
      // download
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=".$fileFromPublic);
      header("Content-Type: application/csv; ");
      // dd(Storage::Exists($fileFromPublic));
      readfile($fileFromPublic);
      // deleting file
      foreach($thingsArray as $csv){
        unlink($csv);
      }
      unlink($fileFromPublic);
      exit();
    }
    // public function exportZip(){
    //   ini_set('memory_limit', '4096M');
    //   ini_set('max_execution_time', '3600');
    //   $patients=new Patient();
    //   $cases = new MedicalCase();
    //   $thingsArray=[];
    //   $things_to_add=["medical_cases","medical_case_answers","nodes","answers","algorithms","versions","answer_types","custom_diagnoses","diagnoses","diagnosis_references","drugs","drug_references","formulations","managements"];
    //   foreach($things_to_add as $table){
    //     array_push($thingsArray,$cases->getDataCsv($table));
    //   }
    //   $filename=$patients->patientData();
    //   $thingsArray = Arr::prepend($thingsArray, $filename);
    //   $zipper = new \Madnest\Madzipper\Madzipper;
    //   $zipper->make("ibu.zip")->add($thingsArray);
    //   $zipper->close();
    //   $fileFromPublic=$path = base_path().'/public/ibu.zip';;
    //   // download
    //   header("Content-Description: File Transfer");
    //   header("Content-Disposition: attachment; filename=".$fileFromPublic);
    //   header("Content-Type: application/csv; ");
    //   // dd(Storage::Exists($fileFromPublic));
    //   readfile($fileFromPublic);
    //   // deleting file
    //   foreach($thingsArray as $csv){
    //     unlink($csv);
    //   }
    //   unlink($fileFromPublic);
    //   exit();
    //   // this is for the patient export-dont delete until you are sure about it.
    //   // return Excel::download(new PatientExport,'patients.csv');
    // }
    public function Patients(){
      return Excel::download(new PatientExport,'patients.csv');
    }
    public function cases(){
      return Excel::download(new Medical_CaseExport,'medical_cases.csv');
    }
    public function casesAnswers(){
      ini_set('memory_limit', '4096M');
      ini_set('max_execution_time', '300');
      return Excel::download(new MedicalCaseAnswerExport,'medical_case_answers.csv');
    }
    public function casesAnswers2(){
      ini_set('memory_limit', '4096M');
      // $allCaseAnswersChunked=MedicalCaseAnswer::all()->chunk(20);
      // return MedicalCaseAnswer::first();
      // $allCaseAnswersChunked->each(function($chunk){
      //   return $chunk;
      // });
      $callback = function(){
        // Open output stream
        $handle = fopen('php://output', 'w');
        // Add CSV headers
        fputcsv($handle, ["id","medical_case_id","answer_id","node_id","value","created_at","updated_at"]);
        $case_answers=MedicalCaseAnswer::all();
        $case_answers->each(function($case_answer)use (&$handle){
          $c_answer=[
            $case_answer->id,
            $case_answer->medical_case_id,
            $case_answer->answer_id,
            $case_answer->node_id,
            $case_answer->value,
            $case_answer->created_at,
            $case_answer->updated_at
          ];
          fputcsv($handle,$c_answer);
        });
        // Close the output stream
        fclose($handle);
    };
    // build response headers so file downloads.
    $headers = ['Content-Type' => 'text/csv',];
    // return the response as a streamed response.
    for ($i=0;$i<7;$i++){
      return response()->streamDownload($callback, 'medical_case_answers.csv', $headers);
    }
    // return response()->streamDownload($callback, 'medical_case_answers.csv', $headers);
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

    public function drugsSummary(){
      $drug_ref0=DrugReference::where('agreed',1)->get();
      $drug_ref0->each(function($drug_ref){
        $drug=Drug::find($drug_ref->drug_id);
        $diagnosis_ref=DiagnosisReference::find($drug_ref->diagnosis_id);
        $drug_ref->case_id=MedicalCase::find($diagnosis_ref->medical_case_id)->local_medical_case_id;
        $diagnosis=Diagnosis::find($diagnosis_ref->diagnosis_id);
        $formulations=Formulation::where('drug_id',$drug->id)->get();
        $drug_ref->formulation=$formulations->get($drug_ref->formulationSelected);
        $drug_ref->drug_label=$drug->label;
        $drug_ref->diagnosis_label=$diagnosis->label;
      });
      return view('drugs.index')->with('drugs',$drug_ref0);
    }
    public function diagnosesSummary(){
      $diagnoses_ref0=DiagnosisReference::where('agreed',true)->get();
      $diagnoses_ref0->each(function($d_f){
        $case=MedicalCase::find($d_f->medical_case_id);
        $d_f->local_medical_case_id=$case->local_medical_case_id;
        $d_f->patient_id=$case->patient->id;
        $d_f->local_patient_id=$case->patient->local_patient_id;
        // $d_f->facility_name=$case->facility->facility_name;
        if($case->facility && $case->facility->facility_name){
          $d_f->facility_name=$case->facility->facility_name;
        }else{
          $case->facility_name='';
        }
        $d_f->diagnosis_label=Diagnosis::find($d_f->diagnosis_id)->label;
        });
      return view('diagnoses.index')->with('diagnoses',$diagnoses_ref0);
    }
}
