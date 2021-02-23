<?php

namespace App\Http\Controllers;
use App\Exceptions\RedCapApiServiceException;
use App\Followup;
use App\Services\RedCapApiService;
use Illuminate\Support\Collection;
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
    $patient->related_ids=implode(',',$patient->related_ids);
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
    // $duplicates = Patient::select('first_name','last_name')
    // ->groupBy('first_name','last_name')
    // ->havingRaw('COUNT(*) > 1')
    // ->get();
      $patients= Patient::where([['merged',0],['status',0]])->get();
      $duplicateArray=[];
      foreach($patients as $patient){
        $keyword=$patient->local_patient_id;
        $patientDuplicate=Patient::where(
          [
            ['other_uid',$patient->local_patient_id],
            ['merged',0],
            ['id','!=' , $patient->id],
            ['status',0]
          ]
          )
          ->orWhere([['merged',0],['status',0],['id','!=' , $patient->id]])->whereJsonContains('related_ids',[$keyword])
          ->get()->toArray();
        if(Patient::where([
          ['other_uid',$patient->local_patient_id],
          ['merged',0],
          ['id','!=' , $patient->id],
          ['status',0]])->exists()){
          array_push($patientDuplicate,$patient->toArray());
          array_push($duplicateArray,$patientDuplicate);
        }
      }
      // find by dulicate is true and check if he already exists in the previous duplicate group
      $markedPatients=Patient::where([
        ['duplicate',1],
        ['merged',0]
      ])->get();
      foreach($markedPatients as $patient){
          $patientDuplicate=Patient::where([
            ['last_name',$patient->last_name],
            ['merged',0],
            ['status',0]
          ])
          ->orWhere([
            ['first_name',$patient->first_name],
            ['merged',0],
            ['status',0]
          ])
          ->orWhere([
            ['birthdate',$patient->birthdate],
            ['merged',0],
            ['status',0]
          ])
          ->get()->toArray();
        if(sizeOf($patientDuplicate) > 1 ){
          $pairExist=False;
          collect($duplicateArray)->each(function ($duplicateGroup) use(&$patientDuplicate,&$pairExist){
            $existingIds=[];$incomingIds=[];
            collect($duplicateGroup)->each(function($arrayPatient)use(&$existingIds){
               array_push($existingIds,$arrayPatient['id']);
            });
            collect($patientDuplicate)->each(function($arrayPatient)use(&$incomingIds){
              array_push($incomingIds,$arrayPatient['id']);
            });
            sort($existingIds);sort($incomingIds);
            if ($existingIds===$incomingIds) {
              $pairExist=True;
            }
          });
          if(!$pairExist){
            array_push($duplicateArray,$patientDuplicate);
          }
        }
      }
    return view('patients.showDuplicates')->with("catchEachDuplicate",$duplicateArray);
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
    if($first_patient->related_ids != null){
      $first_patient->related_ids=implode(',',$first_patient->related_ids);
    }
    if($second_patient->related_ids != null){
      $second_patient->related_ids=implode(',',$second_patient->related_ids);
    }
    // $first_patient->related_ids=implode(',',$first_patient->related_ids);
    // $second_patient->related_ids=implode(',',$second_patient->related_ids);
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
    $criteria=$request->input('searchCriteria');
    if(! $criteria){
      return redirect()->action(
          'PatientsController@findDuplicates'
      );
    }
    $tablecolumns = Schema::getColumnListing('patients');
    if(sizeOf(array_diff($criteria,$tablecolumns))==0){
      if(sizeOf($criteria)==1){
        $scriteria=strval($criteria[0]);
        $duplicates = Patient::select($scriteria)
        ->where([['merged',0],['status',0]])
        ->groupBy($criteria[0])
        ->havingRaw('COUNT(*) > 1')
        ->get()->toArray();
        $catchEachDuplicate=array();
        foreach($duplicates as $duplicate){
          $patients = Patient::where([
            [$scriteria, $duplicate[$scriteria]],
            ['merged',0],
            ['status',0]
          ]
            )->get();
          array_push($catchEachDuplicate,$patients);
        }
        return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
      }
      else if(sizeOf($criteria)==2){
        $duplicates = Patient::select($criteria[0],$criteria[1])
        ->where('merged',0)
        ->groupBy($criteria[0],$criteria[1])
        ->havingRaw('COUNT(*) > 1')
        ->get()->toArray();
        $catchEachDuplicate=array();
        foreach($duplicates as $duplicate){
          $patients = Patient::where(
            [
              [$criteria[0], $duplicate[$criteria[0]]],
              [$criteria[1], $duplicate[$criteria[1]]],
              ['merged',0]
          ]
          )->get();
          array_push($catchEachDuplicate,$patients);
        }
        return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
      }else if(sizeOf($criteria)==3){
        $duplicates = Patient::select($criteria[0],$criteria[1],$criteria[2])
        ->where('merged',0)
        ->groupBy($criteria[0],$criteria[1],$criteria[2])
        ->havingRaw('COUNT(*) > 1')
        ->get()->toArray();
        $catchEachDuplicate=array();
        foreach($duplicates as $duplicate){
          $patients = Patient::where(
            [
              [$criteria[0], $duplicate[$criteria[0]]],
              [$criteria[1], $duplicate[$criteria[1]]],
              [$criteria[2], $duplicate[$criteria[2]]],
              ['merged',0]
          ]
          )->get();
          array_push($catchEachDuplicate,$patients);
        }
        return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
      }else{
        return redirect()->action(
          'PatientsController@findDuplicates'
        );
      }

      // $duplicates = Patient::select("first_name","last_name")
      // ->groupBy("first_name","last_name")
      // ->havingRaw('COUNT(*) > 1')
      // ->get();
      // return $duplicates;
      $catchEachDuplicate=array();
      foreach($duplicates as $duplicate){
        $users = Patient::where($criterias, $duplicate->$criterias)->get();
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
    if($request->has("Keep")){
      $first_patient=Patient::find($request->firstp_id);
      $first_patient->status=1;
      $first_patient->save();
      $second_patient=Patient::find($request->secondp_id);
      $second_patient->status=1;
      $second_patient->save();
      return redirect()->action(
        'PatientsController@findDuplicates'
      )->with('status',' Rows Kept as Non Duplicates');
    }

    //finding the medical cases to update
    $first_patient=Patient::find($request->firstp_id);
    $second_patient=Patient::find($request->secondp_id);

    // testing the ability to carry all id's
    $first_patient_ids=$first_patient->related_ids;
    $second_patient_ids=$second_patient->related_ids;
    $AllrelatedIds= array_filter(
      array_merge(
        array_diff($first_patient_ids, $second_patient_ids),
        array_diff($second_patient_ids, $first_patient_ids)
      )
    );
    if(! in_array($first_patient->local_patient_id,$AllrelatedIds)){
      array_push($AllrelatedIds,$first_patient->local_patient_id);
    }
    if(! in_array($second_patient->local_patient_id,$AllrelatedIds)){
      array_push($AllrelatedIds,$second_patient->local_patient_id);
    }



    $consent_array=array();
    if($first_patient->consent){
      array_push($consent_array,$first_patient->consent);
    }
    if($second_patient->consent){
      array_push($consent_array,$second_patient->consent);
    }
    $consent = serialize($consent_array);
    //finding all the cases that belong to each of the patients

    //creating a new patient
    $hybrid_patient=new Patient([
      'first_name'=>$request->first_name,
      'last_name'=>$request->last_name,
      'local_patient_id'=>collect([$first_patient,$second_patient])->sortByDesc('updated_at')->first()->local_patient_id,
      'birthdate'=>$request->birthdate,
      'weight'=>$request->weight,
      'gender'=>$request->gender,
      'group_id'=>$request->group_id,
      'consent'=>$consent,
      "related_ids"=>$AllrelatedIds
    ]);
    $hybrid_patient->save();

    $first_patient->medicalCases()->each(function($case)use(&$hybrid_patient){
      $case->patient_id=$hybrid_patient->id;
      $case->save();
    });
    $second_patient->medicalCases()->each(function($case)use(&$hybrid_patient){
      $case->patient_id=$hybrid_patient->id;
      $case->save();
    });

    // old code
    // $casesId=array();
    // $first_person_array=array();
    // if(sizeof($first_patient->medicalCases)>0){
    //   foreach($first_patient->medicalCases as $first_medical_case){
    //     $first_medical_case->update([
    //       "patient_id"=>$hybrid_patient->id
    //     ]);
    //     array_push($casesId,$first_medical_case->id);
    //   }
    // }

    // $case_not_to_update=self::relateCases($casesId,$second_patient->medicalCases);

    // if(sizeof($second_patient->medicalCases)>0){
    //   foreach($second_patient->medicalCases as $second_medical_case){
    //     if(!(in_array($second_medical_case->id,$case_not_to_update))){
    //       $second_medical_case->update([
    //         "patient_id"=>$hybrid_patient->id
    //       ]);
    //     }else{
    //       $second_medical_case->diagnosesReferences->each->delete();
    //       $second_medical_case->delete();
    //     }
    //   }
    // }

    //making the first person and second person record termed as merged
    $first_patient->merged=1;
    $first_patient->merged_with=$second_patient->local_patient_id;
    $first_patient->status=1;
    $first_patient->save();

    $second_patient->merged=1;
    $second_patient->merged_with=$first_patient->local_patient_id;
    $second_patient->status=1;
    $second_patient->save();

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
