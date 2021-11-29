<?php

namespace App\Http\Controllers;

use App\Exports\PatientExport;
use App\Jobs\ExportZip;
use App\MedicalCase;
use App\Patient;
use Auth;
use Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PatientsController extends Controller
{
    /**
     * To block any non-authorized user
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:Merge_Duplicates', ['only' => ['findDuplicates', 'mergeShow', 'searchDuplicates', 'merge']]);
        $this->middleware('permission:Delete_Patient', ['only' => ['destroy']]);
    }

    /**
     * View all patients
     * @return $patients
     */
    public function index()
    {
        $patients = Patient::with([
            'facility',
        ])->orderBy('created_at')->get();
        $patients->each(function ($patient) {
            $patient->facility_name =
            ($patient->facility && $patient->facility->name)
            ? $patient->facility->name
            : '';
        });
        return view('patients.index')->with('patients', $patients);
    }

    /**
     * Show individual Patient
     * @params $id
     * @return $patient
     */
    public function show($id)
    {
        $patient = Patient::find($id);
        $patient->related_ids = implode(',', $patient->related_ids);
        return view('patients.showPatient')->with('patient', $patient);
    }

    /**
     * Shows comparison between patients
     * @params $firstId
     * @params $secondId
     * @return $patients
     */
    public function compare($firstId, $secondId)
    {
        $first_patient = Patient::find($firstId);
        $first_patient = $this->FV($first_patient);
        $second_patient = Patient::find($secondId);
        $data = array(
            'first_patient' => $first_patient,
            'second_patient' => $second_patient,
        );
        return view('patients.compare')->with($data);
    }

    /**
     * Find duplicates by a certain value
     * @return View
     * @return $catchEachDuplicate
     */
    public function findDuplicates()
    {
        $patient = new Patient();
        $duplicateArray = $patient->findByUids();
        $duplicateArray = $patient->findByDuplicateKey($duplicateArray);
        $duplicateArray = $patient->checkForPairs($duplicateArray);
        return view('patients.showDuplicates')->with("catchEachDuplicate", $duplicateArray);
    }

    /**
     * Shows merge between patients
     * @params $firstId
     * @params $secondId
     * @return $patients
     */
    public function mergeShow($firstId, $secondId)
    {
        $first_patient = Patient::find($firstId);
        $second_patient = Patient::find($secondId);
        // dd($first_patient->related_ids);
        // if(count($first_patient->related_ids) != 0){
        //   $first_patient->related_ids=implode(',',$first_patient->related_ids);
        // }
        // if(count($second_patient->related_ids) != 0){
        //   $second_patient->related_ids=implode(',',$second_patient->related_ids);
        // }
        $data = array(
            'first_patient' => $first_patient,
            'second_patient' => $second_patient,
        );
        return view('patients.merge')->with($data);
    }

    /**
     * Search duplicates
     * @params $request
     * @return view
     */
    public function searchDuplicates(Request $request)
    {
        $criteria = $request->input('searchCriteria');
        if (!$criteria) {
            return redirect()->action(
                'PatientsController@findDuplicates'
            );
        }
        $tablecolumns = Schema::getColumnListing('patients');
        if (sizeOf(array_diff($criteria, $tablecolumns)) == 0) {
            if (sizeOf($criteria) == 1) {
                $scriteria = strval($criteria[0]);
                $duplicates = Patient::select($scriteria)
                    ->where([['merged', 0], ['status', 0]])
                    ->groupBy($criteria[0])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()->toArray();
                $catchEachDuplicate = array();
                foreach ($duplicates as $duplicate) {
                    $patients = Patient::where([
                        [$scriteria, $duplicate[$scriteria]],
                        ['merged', 0],
                        ['status', 0],
                    ]
                    )->get();
                    array_push($catchEachDuplicate, $patients);
                }
                return view('patients.showDuplicates')->with("catchEachDuplicate", $catchEachDuplicate);
            } else if (sizeOf($criteria) == 2) {
                $duplicates = Patient::select($criteria[0], $criteria[1])
                    ->where('merged', 0)
                    ->groupBy($criteria[0], $criteria[1])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()->toArray();
                $catchEachDuplicate = array();
                foreach ($duplicates as $duplicate) {
                    $patients = Patient::where(
                        [
                            [$criteria[0], $duplicate[$criteria[0]]],
                            [$criteria[1], $duplicate[$criteria[1]]],
                            ['merged', 0],
                        ]
                    )->get();
                    array_push($catchEachDuplicate, $patients);
                }
                return view('patients.showDuplicates')->with("catchEachDuplicate", $catchEachDuplicate);
            } else if (sizeOf($criteria) == 3) {
                $duplicates = Patient::select($criteria[0], $criteria[1], $criteria[2])
                    ->where('merged', 0)
                    ->groupBy($criteria[0], $criteria[1], $criteria[2])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()->toArray();
                $catchEachDuplicate = array();
                foreach ($duplicates as $duplicate) {
                    $patients = Patient::where(
                        [
                            [$criteria[0], $duplicate[$criteria[0]]],
                            [$criteria[1], $duplicate[$criteria[1]]],
                            [$criteria[2], $duplicate[$criteria[2]]],
                            ['merged', 0],
                        ]
                    )->get();
                    array_push($catchEachDuplicate, $patients);
                }
                return view('patients.showDuplicates')->with("catchEachDuplicate", $catchEachDuplicate);
            } else if (sizeOf($criteria) == 4) {
                $duplicates = Patient::select($criteria[0], $criteria[1], $criteria[2], $criteria[3])
                    ->where('merged', 0)
                    ->groupBy($criteria[0], $criteria[1], $criteria[2], $criteria[3])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()->toArray();
                $catchEachDuplicate = array();
                foreach ($duplicates as $duplicate) {
                    $patients = Patient::where(
                        [
                            [$criteria[0], $duplicate[$criteria[0]]],
                            [$criteria[1], $duplicate[$criteria[1]]],
                            [$criteria[2], $duplicate[$criteria[2]]],
                            [$criteria[3], $duplicate[$criteria[3]]],
                            ['merged', 0],
                        ]
                    )->get();
                    array_push($catchEachDuplicate, $patients);
                }
                return view('patients.showDuplicates')->with("catchEachDuplicate", $catchEachDuplicate);
            }
            // else if(sizeOf($criteria)==5){
            //   $duplicates = Patient::select($criteria[0],$criteria[1],$criteria[2],$criteria[3],$criteria[4])
            //   ->where('merged',0)
            //   ->groupBy($criteria[0],$criteria[1],$criteria[2],$criteria[3],$criteria[4])
            //   ->havingRaw('COUNT(*) > 1')
            //   ->get()->toArray();
            //   $catchEachDuplicate=array();
            //   foreach($duplicates as $duplicate){
            //     $patients = Patient::where(
            //       [
            //         [$criteria[0], $duplicate[$criteria[0]]],
            //         [$criteria[1], $duplicate[$criteria[1]]],
            //         [$criteria[2], $duplicate[$criteria[2]]],
            //         [$criteria[3], $duplicate[$criteria[3]]],
            //         [$criteria[4], $duplicate[$criteria[4]]],
            //         ['merged',0]
            //     ]
            //     )->get();
            //     array_push($catchEachDuplicate,$patients);
            //   }
            //   return view('patients.showDuplicates')->with("catchEachDuplicate",$catchEachDuplicate);
            // }
            else {
                return redirect()->action(
                    'PatientsController@findDuplicates'
                );
            }
            $catchEachDuplicate = array();
            foreach ($duplicates as $duplicate) {
                $users = Patient::where($criterias, $duplicate->$criterias)->get();
                array_push($catchEachDuplicate, $users);
            }
            return view('patients.showDuplicates')->with("catchEachDuplicate", $catchEachDuplicate);
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
    public function merge(Request $request)
    {
        $patient = new Patient();
        $first_patient = $patient->find($request->firstp_id);
        $second_patient = $patient->find($request->secondp_id);

        if ($request->has("Keep")) {
            $array_pair = [$first_patient->id, $second_patient->id];
            $patient->keepPairs($array_pair);
            return redirect()->action(
                'PatientsController@findDuplicates'
            )->with('status', ' Rows Kept as Non Duplicates');
        }

        $allrelatedIds = $patient->combinePairIds($first_patient->related_ids, $second_patient->related_ids);
        $allrelatedIds = $patient->addLocalPatientIds($first_patient->local_patient_id, $second_patient->local_patient_id, $allrelatedIds);
        $consent = $patient->addConsentList($first_patient->consent, $second_patient->consent);
        //creating a new patient
        $hybrid_patient = new Patient([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'local_patient_id' => collect([$first_patient, $second_patient])->sortByDesc('updated_at')->first()->local_patient_id,
            'birthdate' => $request->birthdate,
            'weight' => $request->weight,
            'gender' => $request->gender,
            'other_id' => $request->other_id,
            'group_id' => $request->group_id,
            'consent' => $consent,
            "related_ids" => $allrelatedIds,
        ]);
        $hybrid_patient->save();
        $first_patient->medical_cases()->each(function ($case) use (&$hybrid_patient) {
            $case->patient_id = $hybrid_patient->id;
            $case->save();
        });
        $second_patient->medical_cases()->each(function ($case) use (&$hybrid_patient) {
            $case->patient_id = $hybrid_patient->id;
            $case->save();
        });
        //making the first person and second person record termed as merged
        $first_patient->merged = 1;
        $first_patient->merged_with = $second_patient->local_patient_id;
        $first_patient->save();
        $second_patient->merged = 1;
        $second_patient->merged_with = $first_patient->local_patient_id;
        $second_patient->save();

        return redirect()->action(
            'PatientsController@findDuplicates'
        )->with('status', ' New Row Formed!');
    }

    /**
     * Delete a particular patient record
     * @params $request
     * @return View
     */
    public function destroy(Request $request)
    {
        $patient = Patient::find($request->patient_id);
        if ($patient->medical_cases) {
            foreach ($patient->medical_cases as $case) {
                $case->diagnoses_references->each->delete();
            }
            $patient->medical_cases->each->delete();
        }
        if ($patient->delete()) {
            return redirect()->action(
                'PatientsController@findDuplicates'
            )->with('status', 'Row Deleted!');
        }
    }

    public function patientIntoExcel()
    {
        return Excel::download(new PatientExport, 'patients.xlsx');
    }
    public function patientIntoCsv()
    {
        return Excel::download(new PatientExport, 'patients.csv');
    }
    public function allDataIntoExcel()
    {
        // return view('exports.index');

        $user_email = Auth::user()->email;
        $tempFiles = base_path() . '/storage/app/tempExcels/' . $user_email;
        $tempZip = base_path() . '/storage/app/tempZips/' . $user_email . '.zip';
        $file_exist = Storage::exists('tempZips/' . $user_email . '.zip');

        if (Storage::exists('tempExcels/' . $user_email)) {
            Storage::deleteDirectory('tempExcels/' . $user_email);
        }
        if ($file_exist) {
            Storage::delete('tempZips/' . $user_email . '.zip');
        }

        dispatch(new ExportZip($user_email, $tempFiles, $tempZip));
        $file_exist = false;
        while ($file_exist == false) {
            $file_exist = Storage::exists('tempZips/' . $user_email . '.zip');
            if ($file_exist) {
                $zipTime = Carbon\Carbon::now()->addHours(3);
                return response()
                    ->download(
                        $tempZip,
                        $zipTime->format('y-m-d_h:m:s') . '.zip',
                        ['Content-Length: ' . filesize($tempZip)]
                    );
            }
        }
    }

    public function relateCases($casesId, $medicalCases)
    {

        $casesNotToUpdate = array();
        foreach ($medicalCases as $spmd) {
            foreach ($casesId as $caseId) {
                $fpmd = MedicalCase::find($caseId);
                $spnodeIdArray = [];
                foreach ($spmd->medical_case_answers as $ans) {
                    array_push($spnodeIdArray, $ans->node_id);
                }
                $fpnodeIdArray = [];
                foreach ($fpmd->medical_case_answers as $ans) {
                    array_push($fpnodeIdArray, $ans->node_id);
                }
                $difference = array_diff($spnodeIdArray, $fpnodeIdArray);
                $difference2 = array_diff($fpnodeIdArray, $spnodeIdArray);
                // if there is no difference,cupture the second case id
                if (!$difference && !$difference2) {
                    array_push($casesNotToUpdate, $spmd->id);
                }
            }
        }
        return $casesNotToUpdate;
    }
}
