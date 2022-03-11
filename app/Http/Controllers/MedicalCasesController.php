<?php

namespace App\Http\Controllers;

use Excel;
use App\Node;
use App\User;
use App\Answer;
use App\Patient;
use Carbon\Carbon;
use App\AnswerType;
use App\MedicalCase;
use App\HealthFacility;
use App\MedicalCaseAnswer;
use App\DiagnosisReference;
use App\Jobs\RemoveFollowUp;
use Illuminate\Http\Request;
use App\Exports\MedicalCaseExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class MedicalCasesController extends Controller
{
    /**
     * To block any non-authorized user
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(MedicalCase::class);
    }

    /**
     * Display all medical Cases
     * @return View,
     * @return $medicalCases
     */
    public function index()
    {
        $medical_cases = MedicalCase::with([
            'patient',
            'patient.facility',
        ])->orderBy('created_at')->get();
        $medical_cases->each(function ($case) {
            $case->facility_name =
                ($case->patient->facility && $case->patient->facility->name)
                ? $case->patient->facility->name
                : '';
        });
        return view('medicalCases.index')->with('medicalCases', $medical_cases);
    }

    /**
     * Show specific medical Case
     * @params $id
     * @return View
     * @return $data
     */
    public function show(MedicalCase $medical_case)
    {
        $medical_case_info = array();
        foreach ($medical_case->medical_case_answers as $medical_case_answer) {
            if ($medical_case_answer->answer_id == 0) {
                $data = array(
                    "answer" => $medical_case_answer->value,
                    "question" => Node::find($medical_case_answer->node_id),
                );
                array_push($medical_case_info, json_decode(json_encode($data)));
            } else {
                $data = array(
                    "answer" => Answer::find($medical_case_answer->answer_id)->label,
                    "question" => Node::find($medical_case_answer->node_id),
                );
                array_push($medical_case_info, json_decode(json_encode($data)));
            }
        }
        $diagnoses = DiagnosisReference::getDiagnoses($medical_case->id);
        $data = array(
            'medicalCase' => $medical_case,
            'medicalCaseInfo' => $medical_case_info,
            'diagnoses' => $diagnoses,
        );
        return view('medicalCases.show')->with($data);
    }

    /**
     * Compare between two Medical cases
     * @params firstId
     * @params secondId
     * @return view
     * @return $data
     */
    public function compare($first_id, $second_id)
    {
        $first_medical_case = MedicalCase::find($first_id);
        $second_medical_case = MedicalCase::find($second_id);
        $medical_case_info = $this->comparison($first_medical_case, $second_medical_case);
        $data = array(
            'first_medical_case' => $first_medical_case,
            'second_medical_case' => $second_medical_case,
            'medical_case_info' => $medical_case_info,
        );
        return view('medicalCases.compare')->with($data);
    }

    /**
     * Compare questions between two Medical cases
     * @params first_medical_case
     * @params second_medical_case
     * @return $all_questions
     */
    public static function comparison($first_medical_case, $second_medical_case)
    {
        $first_case_questions = [];
        $first_medical_case->medical_case_answers->each(function ($answer) use (&$first_case_questions) {
            $first_case_questions[] = $answer->node_id;
        });
        $second_case_questions = [];
        $second_medical_case->medical_case_answers->each(function ($answer) use (&$second_case_questions) {
            $second_case_questions[] = $answer->node_id;
        });
        $common_questions_id = array_intersect(
            $first_case_questions,
            $second_case_questions
        );
        $common_questions = [];
        foreach ($common_questions_id as $question_id) {
            $first_case_answer = $first_medical_case->medical_case_answers->where('node_id', $question_id)->first();
            $first_answer = $first_case_answer->value;
            if ($first_case_answer->answer) {
                $first_answer = $first_case_answer->answer->label;
            }
            $second_case_answer = $second_medical_case->medical_case_answers->where('node_id', $question_id)->first();
            $second_answer = $first_case_answer->value;
            if ($second_case_answer->answer) {
                $second_answer = $second_case_answer->answer->label;
            }
            $common_questions[] = array(
                "medal_c_id" => $second_case_answer->node->medal_c_id,
                "question_label" => $second_case_answer->node->label,
                "question_stage" => $second_case_answer->node->stage,
                "first_answer" => $first_answer,
                "second_answer" => $second_answer,
            );
        }
        $uncommon_questions_id = array_merge(
            array_diff($first_case_questions, $second_case_questions),
            array_diff($second_case_questions, $first_case_questions)
        );
        $uncommon_questions = [];
        foreach ($uncommon_questions_id as $question_id) {
            // finding for the first medical case
            $first_case_answer = $first_medical_case->medical_case_answers->where('node_id', $question_id)->first();
            if ($first_case_answer) {
                $first_answer = $first_case_answer->value;
                if ($first_case_answer->answer) {
                    $first_answer = $first_case_answer->answer->label;
                }
                $uncommon_questions[] = array(
                    "medal_c_id" => $first_case_answer->node->medal_c_id,
                    "question_label" => $first_case_answer->node->label,
                    "question_stage" => $first_case_answer->node->stage,
                    "first_answer" => $first_answer,
                    "second_answer" => $second_answer = null,
                );
            }
            $second_case_answer = $second_medical_case->medical_case_answers->where('node_id', $question_id)->first();
            if ($second_case_answer) {
                $second_answer = $second_case_answer->value;
                if ($second_case_answer->answer) {
                    $second_answer = $second_case_answer->answer->label;
                }
                $uncommon_questions[] = array(
                    "medal_c_id" => $second_case_answer->node->medal_c_id,
                    "question_label" => $second_case_answer->node->label,
                    "question_stage" => $second_case_answer->node->stage,
                    "first_answer" => $first_answer = null,
                    "second_answer" => $second_answer,
                );
            }
        }
        $all_questions = array_merge($common_questions, $uncommon_questions);
        $all_questions = array_filter($all_questions, function ($question) {
            return !(empty($question["first_answer"]) && empty($question["second_answer"]));
        });
        return $all_questions;
    }

    /**
     * Display an answer of a specific medical case
     * @params $medicalCaseId
     * @params $questionId
     * @return View
     * @return $question
     */
    public function medicalCaseQuestion($medical_case_id, $question_id)
    {
        $this->authorize('question', MedicalCase::class);

        $answers = [];
        $question = Node::find($question_id);
        $answer_type = AnswerType::find($question->answer_type_id);
        foreach ($question->answers as $answer) {
            $answers[$answer->id] = $answer->label;
        }
        $data = array(
            "medicalCase" => MedicalCase::find($medical_case_id),
            "question" => $question,
            "answers" => $answers,
            "answer_type" => $answer_type,
        );
        return view('medicalCases.question')->with($data);
    }

    /**
     * Find the details of the medical case
     * @params $medical Case
     * @params $label_info
     * @params $medical_case_info
     * @return $medical_case_info
     */
    public static function detailFind($medical_case, $label_info, $medical_case_info = array())
    {
        foreach ($medical_case->medical_case_answers as $medicalCaseAnswer) {
            if ($medicalCaseAnswer->answer_id == 0) {
                $answer = $medicalCaseAnswer->value;
                $question = Node::find($medicalCaseAnswer->node_id);
                // $medicalCaseAnswer = $medicalCaseAnswer;
                $medical_case_info[$question->id]["question"] = $question;
                $medical_case_info[$question->id][$label_info] = array(
                    "answer" => $answer,
                    "medicalCaseAnswer" => $medicalCaseAnswer,
                );
            } else {
                $answer = Answer::find($medicalCaseAnswer->answer_id);
                $question = Node::find($medicalCaseAnswer->node_id);
                // $medicalCaseAnswer = $medicalCaseAnswer;
                $medical_case_info[$question->id]["question"] = $question;
                $medical_case_info[$question->id][$label_info] = array(
                    "answer" => $answer,
                    "medicalCaseAnswer" => $medicalCaseAnswer,
                );
            }
        }
        return $medical_case_info;
    }

    /**
     * Show Audit Trail of a particular medical Case
     * @params $medical_case_id
     * @return View
     * @return $medicalCaseAudits
     */
    public function showCaseChanges($medical_case_id)
    {
        $this->authorize('changes', MedicalCase::class);

        $medicalCase = MedicalCase::find($medical_case_id);
        $all_audits = array();
        foreach ($medicalCase->medical_case_answers as $medicalCaseAnswer) {
            $medicalCaseAudit = MedicalCaseAnswer::getAudit($medicalCaseAnswer->id);
            $medicalCaseAuditSize = sizeof($medicalCaseAudit->toArray());
            if ($medicalCaseAuditSize > 0) {
                foreach ($medicalCaseAudit as $audit) {
                    $audit_array = array(
                        "user" => User::find($audit->user_id)->name,
                        "question" => Node::find($medicalCaseAnswer->node_id)->label,
                        "old_value" => Answer::find($audit->old_values["answer_id"])->label,
                        "new_value" => Answer::find($audit->new_values["answer_id"])->label,
                        "url" => $audit->url,
                        "event" => $audit->event,
                        "ip_address" => $audit->ip_address,
                        "created_at" => $audit->created_at,
                    );
                    array_push($all_audits, $audit_array);
                }
            }
        }
        $data = array(
            "allAudits" => $all_audits,
            "medicalCaseId" => $medical_case_id,
        );
        return view('medicalCases.showCaseChanges')->with($data);
    }

    /**
     * Find duplicates by a certain value
     * @return View
     * @return $catchEachDuplicate
     */
    public function findDuplicates()
    {
        return view('medicalCases.showDuplicates2');
    }

    public function findDuplicates2()
    {
        $case_columns = ['patient_id', DB::raw('Date(consultation_date)'), DB::raw('COUNT(*) as count')];

        $medicalCases = MedicalCase::with(['patient', 'patient.facility'])
            ->where('duplicate', false)
            ->whereDate('consultation_date', '<=', Carbon::now())
            ->whereIn('medical_cases.patient_id', function ($query) use ($case_columns) {
                $query->select($case_columns[0])
                    ->fromSub(function ($query) use ($case_columns) {
                        $query->select($case_columns)
                            ->from('medical_cases')
                            ->groupBy(DB::raw('Date(consultation_date)'), 'patient_id')
                            ->havingRaw('COUNT(*) > 1');
                    }, 'medical_cases');
            })
            ->get();

        $medicalCases->each(function (MedicalCase $medicalCase) {
            $medicalCase->hf = $medicalCase->patient->facility->name ?? '';
            $medicalCase->consultation_date = Carbon::createFromFormat('Y-m-d H:i:s', $medicalCase->consultation_date)->format('Y-m-d');
        });

        $medicalCases->groupBy(function ($item) {
            return $item['consultation_date'] . $item['patient_id'];
        });

        return response()->json(["mcs" => array_values($medicalCases->toArray())]);
    }

    public function deduplicate_redcap(Request $request)
    {
        $validated = $request->validate([
            'medicalc_id' => 'required',
        ]);
        $medicalCase = MedicalCase::find((int) $request->input('medicalc_id'));
        dispatch((new RemoveFollowUp($medicalCase))->onQueue("high"));
        return redirect()->action(
            'medical-cases.findDuplicates'
        )->with('status', "Follow Up for '{$medicalCase->local_medical_case_id}' is Queued for removal in redcap");
    }

    /**
     * Search duplicates
     * @params $request
     * @return view
     */
    public function searchDuplicates(Request $request)
    {
        $this->authorize('duplicates', MedicalCase::class);

        $columns = Schema::getColumnListing('medical_cases');
        $search_value = $request->search;
        if (in_array($search_value, $columns)) {
            $duplicates = MedicalCase::select($search_value)
                ->groupBy($search_value)
                ->havingRaw('COUNT(*) > 1')
                ->get();
            $catch_each_duplicate = array();
            foreach ($duplicates as $duplicate) {
                $case_duplicates = MedicalCase::where($search_value, $duplicate->$search_value)->get();
                array_push($catch_each_duplicate, $case_duplicates);
            }
            return view('medicalCases.showDuplicates')->with("catchEachDuplicate", $catch_each_duplicate);
        }
        return redirect()->action(
            'medical-cases.findDuplicates'
        );
    }

    /**
     * Delete a particular medical case record
     * @params $request
     * @return View
     */
    public function destroy(Request $request)
    {
        $medical_case = Patient::find($request->medicalc_id);
        if ($medical_case->medical_case_answers) {
            $medical_case->medical_case_answers->each->delete();
        }
        $old_medical_case = clone $medical_case;
        if ($medical_case->delete()) {
            Log::info("User with id " . Auth::user()->id . " removed a medical case.", ["medical_case" => $old_medical_case]);
            return redirect()->action(
                'MedicalCasesController@findDuplicates'
            )->with('status', 'Row Deleted!');
        }
    }

    public function followUpDelayed()
    {
        $this->authorize('followUp', MedicalCase::class);

        $all_unfollowed = new MedicalCase;
        return view('medicalCases.unfollowed')->with("unfollowed", $all_unfollowed->listUnfollowed());
    }

    public function showFacilities()
    {
        $this->authorize('followUp', MedicalCase::class);
        return view('medicalCases.followed')->with("facilities", $facilities = HealthFacility::all());
    }

    public function showFacility($id)
    {
        $this->authorize('followuP', MedicalCase::class);

        $facility_exist = HealthFacility::where('group_id', $id)->first();
        if (!$facility_exist) {
            return response()->json([
                "facility" => false,
            ]);
        }

        $patients = Patient::where('group_id', $id)->get();
        $count_redcap_sent = 0;
        $count_redcap_unsent = 0;
        foreach ($patients as $patient) {
            foreach ($patient->medical_cases as $case) {
                if ($case->redcap) {
                    $count_redcap_sent += 1;
                } else {
                    $count_redcap_unsent += 1;
                }
            }
        }
        return response()->json([
            "facility" => true,
            "data" => [
                "redcap_sent" => $count_redcap_sent,
                "redcap_unsent" => $count_redcap_unsent,
            ],
        ]);
    }

    // public function findDuplicates(){
    //   return 'here';
    // }
    public function medicalCaseIntoExcel()
    {
        return Excel::download(new MedicalCaseExport, 'medicalCases.xlsx');
    }

    public function medicalCaseIntoCsv()
    {
        return Excel::download(new MedicalCaseExport, 'medicalCases.csv');
    }
}
