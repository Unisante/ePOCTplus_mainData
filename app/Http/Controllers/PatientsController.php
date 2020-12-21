<?php

namespace App\Http\Controllers;
use App\Services\RedCapApiService;
use Illuminate\Support\Facades\Schema;
use App\Patient;
use App\Answer;
use App\MedicalCase;
use App\User;
use App\Node;
use App\DiagnosisReference;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use App\Exports\PatientExport;
use App\Exports\MedicalCaseExport;
use App\Exports\DataSheet;
use Excel;

class PatientsController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');

    $this->middleware('permission:Merge_Duplicates', ['only' => ['findDuplicates','mergeShow','searchDuplicates','merge']]);
    $this->middleware('permission:Delete_Patient',['only'=>['destroy']]);
  }

  /**
  * View all patients
  * @return $patients
  */
  public function index(){
    $patients=Patient::orderBy('created_at')->get();
    return view('patients.index')->with('patients',$patients);
  }

  /**
  * Show individual Patient
  * @params $id
  * @return $patient
  */
  public function show($id){
    $patient=Patient::find($id);
    return view('patients.showPatient')->with('patient',$patient);
  }

  /**
  * Shows comparison between patients
  * @params $firstId
  * @params $secondId
  * @return $patients
  */
  public function compare($firstId,$secondId){
    $first_patient =  Patient::find($firstId);
    $second_patient = Patient::find($secondId);
    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient,
    );
    return view('patients.compare')->with($data);
  }


  /**
  * Find duplicates by a certain value
  * @return View
  * @return $catchEachDuplicate
  */
  public function findDuplicates(){
    $duplicates = Patient::select('first_name','last_name')
    ->groupBy('first_name','last_name')
    ->havingRaw('COUNT(*) > 1')
    ->get();
    $catchEachDuplicate=array();
    foreach($duplicates as $duplicate){
      $users = Patient::where('first_name', $duplicate->first_name)->get();
      array_push($catchEachDuplicate,$users);
    }
    return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
  }

  /**
  * Shows merge between patients
  * @params $firstId
  * @params $secondId
  * @return $patients
  */
  public function mergeShow($firstId,$secondId){
    $first_patient =  Patient::find($firstId);
    $second_patient = Patient::find($secondId);
    $data=array(
      'first_patient'=>$first_patient,
      'second_patient'=>$second_patient,
    );
    return view('patients.merge')->with($data);
  }

  /**
   * Search duplicates
   * @params $request
   * @return view
   */
  public function searchDuplicates(Request $request){
    $columns = Schema::getColumnListing('patients');
    $search_value=$request->search;
    if(in_array($search_value, $columns)){
      $duplicates = Patient::select($search_value)
      ->groupBy($search_value)
      ->havingRaw('COUNT(*) > 1')
      ->get();
      $catchEachDuplicate=array();
      foreach($duplicates as $duplicate){
        $users = Patient::where($search_value, $duplicate->$search_value)->get();
        array_push($catchEachDuplicate,$users);
      }
      return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
    }
    return redirect()->action(
      'PatientsController@findDuplicates'
    );
  }

  /**
  * Merge between two records
  * @params $request
  * @return PatientsController@findDuplicates
  */
  public function merge(Request $request){
    //finding the medical cases to update
    $first_patient=Patient::find($request->firstp_id);
    $second_patient=Patient::find($request->secondp_id);

    $consent_array=array();
    if($first_patient->consent){
      array_push($consent_array,$first_patient->consent);
    }
    if($second_patient->consent){
      array_push($consent_array,$second_patient->consent);
    }

    $consent = serialize($consent_array);
    //creating a new patient
    $hybrid_patient=new Patient([
      'first_name'=>$request->first_name,
      'last_name'=>$request->last_name,
      'local_patient_id'=>$request->local_patient_id,
      'birthdate'=>$request->birthdate,
      'weight'=>$request->weight,
      'gender'=>$request->gender,
      'group_id'=>$request->group_id,
      'consent'=>$consent,
    ]);
    $hybrid_patient->save();

    // new code
    $casesId=array();
    $first_person_array=array();
    if(sizeof($first_patient->medicalCases)>0){
      foreach($first_patient->medicalCases as $first_medical_case){
        $first_medical_case->update([
          "patient_id"=>$hybrid_patient->id
        ]);
        array_push($casesId,$first_medical_case->id);
      }
    }
    $case_not_to_update=self::relateCases($casesId,$second_patient->medicalCases);

    if(sizeof($second_patient->medicalCases)>0){
      foreach($second_patient->medicalCases as $second_medical_case){
        if(!(in_array($second_medical_case->id,$case_not_to_update))){
          $second_medical_case->update([
            "patient_id"=>$hybrid_patient->id
          ]);
        }else{
          $second_medical_case->diagnosesReferences->each->delete();
          $second_medical_case->delete();
        }
      }
    }

    // old code
    // $first_person_array=array();
    // if(sizeof($first_patient->medicalCases)>0){
    //   foreach($first_patient->medicalCases as $first_medical_case){
    //     $first_medical_case->update([
    //       "patient_id"=>$hybrid_patient->id
    //     ]);
    //     array_push($first_person_array,$first_medical_case->medical_case_answers->count());
    //   }
    // }
    // if(sizeof($second_patient->medicalCases)>0){
    //   foreach($second_patient->medicalCases as $second_medical_case){
    //     if(!(in_array($second_medical_case->medical_case_answers->count(),$first_person_array))){
    //       $second_medical_case->update([
    //         "patient_id"=>$hybrid_patient->id
    //       ]);
    //     }else{
    //       $second_medical_case->diagnosesReferences->each->delete();
    //       $second_medical_case->delete();
    //     }
    //   }
    // }

    //deleting first person and second person
    $first_patient->delete();
    $second_patient->delete();

    return redirect()->action(
      'PatientsController@findDuplicates'
    )->with('status',' New Row Formed!');
  }

  /**
  * Delete a particular patient record
  * @params $request
  * @return View
  */
  public function destroy(Request $request){
    $patient=Patient::find($request->patient_id);
    if($patient->medicalCases){
      foreach($patient->medicalCases as $case){
        $case->diagnosesReferences->each->delete();
      }
      $patient->medicalCases->each->delete();
    }
    if($patient->delete()){
      return redirect()->action(
        'PatientsController@findDuplicates'
      )->with('status','Row Deleted!');
    }
  }

  public function patientIntoExcel(){
    return Excel::download(new PatientExport,'patients.xlsx');
  }
  public function patientIntoCsv(){
    return Excel::download(new PatientExport,'patients.csv');
  }
  public function allDataIntoExcel(){
    // return Excel::download(new PatientExport,'patients.csv');
    // return Excel::download(new MedicalCaseExport,'Medicase.csv');
    return Excel::download(new DataSheet,'MainData.xlsx');
  }

  public function relateCases($casesId,$medicalCases){
    $casesNotToUpdate=array();
    foreach($medicalCases as $spmd){
      foreach($casesId as $caseId){
        $fpmd=MedicalCase::find($caseId);
        $spnodeIdArray=[];
        foreach($spmd->medical_case_answers as $ans){
          array_push($spnodeIdArray,$ans->node_id);
        }
        $fpnodeIdArray=[];
        foreach($fpmd->medical_case_answers as $ans){
          array_push($fpnodeIdArray,$ans->node_id);
        }
        $difference = array_diff($spnodeIdArray,$fpnodeIdArray);
        $difference2 = array_diff($fpnodeIdArray,$spnodeIdArray);
        // if there is no difference,cupture the second case id
        if(! $difference && ! $difference2 ){
          array_push($casesNotToUpdate,$spmd->id);
        }
      }
    }
    return $casesNotToUpdate;
  }
}
