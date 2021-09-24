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
use App\MedicalCaseAnswer;
use Illuminate\Support\Arr;
use App\User;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Services\CsvExport;
use Illuminate\Support\Facades\File;

class ExportsController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function selectDate(){
      if(Patient::all()->count() == 0){
        return back()->withErrors("We Currently Do not have records in the database");
      }

      $date_array= [];
      MedicalCase::all()->each(function($case) use(&$date_array){
        if($case->consultation_date == null ){
          $case->consultation_date = $case->created_at;
          $case->save();
        }

        $curDate = strtotime($case->consultation_date);
        array_push($date_array,$curDate);
      });

      $data=array(
        'currentUser'   => Auth::user(),
        'userCount'     => User::all()->count(),
        'mdCases'       => MedicalCase::all()->count(),
        'oldest_date'   => date('Y-m-d', min($date_array)),
        'newest_date'   => date('Y-m-d', max($date_array)),
        'patientCount'  => Patient::all()->count(),

      );

      return view('exports.index')->with($data);;
    }

    public function exportFlatZip($fromDate, $toDate){
      ini_set('memory_limit', '4096M');
      ini_set('max_execution_time', '3600');

      $caseAnswers = new MedicalCaseAnswer();
      if($caseAnswers::all()->count() == 0){
        return back()->withErrors("We currently do not have records in the database.");
      }

      $file_name = 'ibuFlat.csv';
      return $caseAnswers->makeFlatCsv($file_name, $fromDate, $toDate);
    }

    public function exportZipByDate(Request $request){
      ini_set('memory_limit', '4096M');
      ini_set('max_execution_time', '3600');

      if(Patient::all()->count() == 0){
        return back()->withErrors("We currently do not have records in the database.");
      }

      $request->validate(array(
        'fromDate' => 'required|date',
        'toDate' => 'required|date',
      ));

      // get and check dates
      $fromDate = new DateTime($request->input('fromDate'));
      $toDate = new DateTime($request->input('toDate'));
      if($fromDate > $toDate){
        return back()->withErrors("Invalid date interval.");
      }
      if($toDate > Carbon::now() || $toDate > Carbon::now()){
        return back()->withErrors("Date cannot be in the future.");
      }

      if(Arr::exists($request->input(),'DownloadFlat')){
        $today=Carbon::now()->format('Y_m_d');
        $extract='ibu_flat';
        $zipper = new \Madnest\Madzipper\Madzipper;
        $path = storage_path().'/app/flat_files';
        $d = File::allFiles($path);
        $zipper->make($extract.".zip")->add($d);
        $zipper->close();
        $fileFromPublic=base_path().'/public/'.$extract.'.zip';
        
        // download
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=".$fileFromPublic);
        header("Content-Type: application/csv; ");
        readfile($fileFromPublic);
        unlink($fileFromPublic);
        exit();

      }else if(Arr::exists($request->input(),'DownloadSeparate')){
        $csv_export = new CsvExport();
        $csv_export->exportDataByDate($fromDate, $toDate);
        exit();

      }else{
        return back()->withErrors("Something went wrong.");
      }


    }
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