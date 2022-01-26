<?php

namespace App\Http\Controllers;

use App\Patient;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $this->authorizeResource(Patient::class, 'patient');
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
     * @params $patient
     * @return $patient
     */
    public function show(Patient $patient)
    {
        // $patient = Patient::find($id);
        $patient->related_ids = implode(',', $patient->related_ids);
        Log::info("User with id " . Auth::user()->id . " checked out patient " . $patient->id . ".");

        return view('patients.show', [
            'patient' => $patient,
        ]);
    }

    /**
     * Shows comparison between patients
     * @params $firstId
     * @params $secondId
     * @return $patients
     */
    public function compare($firstId, $secondId)
    {
        $this->authorize('duplicate', Patient::class);
        $first_patient = Patient::find($firstId);
        //$first_patient = $this->FV($first_patient);
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
        $this->authorize('duplicate', Patient::class);

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
        $this->authorize('merge', Patient::class);

        $first_patient = Patient::find($firstId);
        $second_patient = Patient::find($secondId);
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
        $this->authorize('duplicate', Patient::class);

        $criteria = $request->input('searchCriteria');
        if (!$criteria) {
            return redirect()->action('PatientsController@findDuplicates');
        }
        $tablecolumns = Schema::getColumnListing('patients');
        if (sizeOf(array_diff($criteria, $tablecolumns)) == 0) {
            if (sizeOf($criteria) == 1) {
                $scriteria = strval($criteria[0]);
                $duplicates = Patient::select($scriteria)
                    ->where([['merged', 0], ['status', 0]])
                    ->groupBy($criteria[0])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()
                    ->toArray();
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
                return view('patients.showDuplicates')
                    ->with("catchEachDuplicate", $catchEachDuplicate);
            } else if (sizeOf($criteria) == 2) {
                $duplicates = Patient::select($criteria[0], $criteria[1])
                    ->where('merged', 0)
                    ->groupBy($criteria[0], $criteria[1])
                    ->havingRaw('COUNT(*) > 1')
                    ->get()
                    ->toArray();
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
                    ->get()
                    ->toArray();
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
                    ->get()
                    ->toArray();
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
            } else {
                return redirect()->action('PatientsController@findDuplicates');
            }

        }
        return redirect()->action('PatientsController@findDuplicates');
    }

    /**
     * Merge between two records
     * @params $request
     * @return PatientsController@findDuplicates
     */
    public function merge(Request $request)
    {
        $this->authorize('merge', Patient::class);

        $patient = new Patient();
        $first_patient = $patient->find($request->firstp_id);
        $second_patient = $patient->find($request->secondp_id);
        if ($request->has("Keep")) {
            $array_pair = [$first_patient->id, $second_patient->id];
            $patient->keepPairs($array_pair);
            return redirect()->action('PatientsController@findDuplicates')->with('status', ' Rows Kept as Non Duplicates');
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
        $old_patient = clone $patient;
        if ($patient->delete()) {
            Log::info("User with id " . Auth::user()->id . " removed a patient.", ["patient" => $old_patient]);
            return redirect()->action(
                'PatientsController@findDuplicates'
            )->with('status', 'Row Deleted!');
        }
    }
}
