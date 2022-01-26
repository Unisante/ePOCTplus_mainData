<?php

namespace App\Http\Controllers;

use App\DiagnosisReference;
use App\DrugReference;
use App\Exports\AdditionalDrugExport;
use App\Exports\AlgorithmExport;
use App\Exports\AnswerExport;
use App\Exports\AnswerTypeExport;
use App\Exports\CustomDiagnosisExport;
use App\Exports\DiagnosisExport;
use App\Exports\DiagnosisReferenceExport;
use App\Exports\DrugExport;
use App\Exports\DrugReferenceExport;
use App\Exports\FormulationExport;
use App\Exports\ManagementExport;
use App\Exports\ManagementReferenceExport;
use App\Exports\MedicalCaseAnswerExport;
use App\Exports\Medical_CaseExport;
use App\Exports\NodeExport;
use App\Exports\PatientExport;
use App\Exports\VersionExport;
use App\MedicalCase;
use App\MedicalCaseAnswer;
use App\Patient;
use App\Services\ExportCsvFlat;
use App\Services\ExportCsvSeparate;
use App\User;
use Auth;
use Carbon\Carbon;
use DateTime;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ExportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private static function checkGateAllows()
    {
        if (!Auth::user()->isAdministrator() && !Gate::allows('export', Auth::user())) {
            abort(403, 'You are not authorized to export data.');
        }
    }

    public function exportZipByDate(Request $request)
    {
        self::checkGateAllows();

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '300');
        ini_set('default_socket_timeout', '300');

        if (Patient::count() == 0) {
            return back()->withErrors("We currently do not have records in the database.");
        }

        $request->validate(array(
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
        ));

        // get and check dates
        $fromDate = new DateTime($request->input('fromDate'));
        $toDate = new DateTime($request->input('toDate'));
        if ($fromDate > $toDate) {
            Log::error("User with id " . Auth::user()->id . " entered an invalid date interval when trying to export data.");
            return back()->withErrors("Invalid date interval.");
        }
        if ($toDate > Carbon::now() || $toDate > Carbon::now()) {
            Log::error("User with id " . Auth::user()->id . " entered an invalid date when trying to export data.");
            return back()->withErrors("Date cannot be in the future.");
        }

        $extract_file_name = Config::get('csv.public_extract_name');
        $file_from_public = base_path() . '/public/' . $extract_file_name . '.zip';
        // generate the data file.
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($extract_file_name . '.zip');
        // check export mode
        if (Arr::exists($request->input(), 'DownloadFlat')) {
            Log::info("User with id " . Auth::user()->id . " started a flat export with date intervals [" . $fromDate->format('Y-m-d') . " - " . $toDate->format('Y-m-d') . "].");
            MedicalCase::with([
                'patient',
                'patient.facility',
                'custom_diagnoses',
                'custom_diagnoses.custom_drugs',
                'version',
                'facility',
                'medical_case_answers',
                'medical_case_answers.answer',
                'medical_case_answers.node',
                'diagnoses_references',
                'diagnoses_references.drug_references',
            ])->chunk(50, function ($medical_case, $key) use ($fromDate, $toDate) {
                $csv_export = new ExportCsvFlat($medical_case, $fromDate, $toDate, $key);
                $csv_export->export();
            });
            $zipper->add(public_path(Config::get('csv.flat.folder') . 'answers.csv'));

        } else if (Arr::exists($request->input(), 'DownloadSeparate')) {
            Log::info("User with id " . Auth::user()->id . " started a separate export with date intervals [" . $fromDate->format('Y-m-d') . " - " . $toDate->format('Y-m-d') . "].");
            MedicalCase::with([
                'activities',
                'patient',
                'patient.facility',
                'custom_diagnoses',
                'custom_diagnoses.custom_drugs',
                'version',
                'version.algorithm',
                'facility',
                'medical_case_answers',
                'medical_case_answers.answer',
                'medical_case_answers.node',
                'medical_case_answers.node.answers',
                'diagnoses_references',
                'diagnoses_references.diagnoses',
                'diagnoses_references.drug_references',
                'diagnoses_references.drug_references.drugs',
                'diagnoses_references.drug_references.drugs.formulations',
                'diagnoses_references.drug_references.drugs.additional_drugs',
                'diagnoses_references.management_references',
                'diagnoses_references.management_references.managements',
            ])->chunk(50, function ($medical_case, $key) use ($fromDate, $toDate) {
                $csv_export = new ExportCsvSeparate($medical_case, $fromDate, $toDate, $key);
                $csv_export->export();
            });
            $zipper->add(public_path(Config::get('csv.folder_separated')));

        } else {
            Log::error("Something went wrong with the last export.");
            return back()->withErrors("Something went wrong.");
        }

        $zipper->close();

        // download the data file.
        $from_date_str = $fromDate->format('Y-m-d');
        $to_date_str = $toDate->format('Y-m-d');

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . Config::get('csv.public_extract_name') . '_' . $from_date_str . '_' . $to_date_str . '.zip');
        header("Content-Type: application/csv; ");
        readfile($file_from_public);

        // delete the data files.
        if (Arr::exists($request->input(), 'DownloadFlat')) {
            File::deleteDirectory(public_path(Config::get('csv.flat.folder')));
        }
        if (Arr::exists($request->input(), 'DownloadSeparate')) {
            File::deleteDirectory(public_path(Config::get('csv.folder_separated')));
        }
        unlink($file_from_public);
        Log::info("Last export finished.");
        exit;
    }

    public function selectDate()
    {
        self::checkGateAllows();

        if (Patient::count() == 0) {
            return back()->withErrors("We Currently do not have any records in the database");
        }

        $date_array = [];
        MedicalCase::all()->each(function ($case) use (&$date_array) {
            if ($case->consultation_date == null) {
                $case->consultation_date = $case->created_at;
                $case->save();
            }

            $curDate = strtotime($case->consultation_date);
            array_push($date_array, $curDate);
        });
        $export_path = storage_path('app/export/');
        if (File::exists($export_path)) {
            $files = File::files($export_path);

        }

        $data = array(
            'currentUser' => Auth::user(),
            'userCount' => User::count(),
            'mdCases' => MedicalCase::count(),
            'oldest_date' => date('Y-m-d', min($date_array)),
            'newest_date' => date('Y-m-d', max($date_array)),
            'patientCount' => Patient::count(),
            'files' => $files ?? [],
        );

        return view('exports.index')->with($data);
    }

    public function DownloadExport($file)
    {
        self::checkGateAllows();

        $file_path = storage_path('app/export/' . $file);
        if (!File::exists($file_path)) {
            return redirect()->back()->withErrors('File not found');
        }
        return response()->download($file_path, Carbon::today()->format('d-m-Y') . '-' . $file);
    }

    public function Patients()
    {
        self::checkGateAllows();
        return Excel::download(new PatientExport, 'patients.csv');
    }
    public function cases()
    {
        self::checkGateAllows();
        return Excel::download(new Medical_CaseExport, 'medical_cases.csv');
    }
    public function casesAnswers()
    {
        self::checkGateAllows();

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '300');
        return Excel::download(new MedicalCaseAnswerExport, 'medical_case_answers.csv');
    }
    public function casesAnswers2()
    {
        self::checkGateAllows();

        ini_set('memory_limit', '4096M');
        $callback = function () {
            // Open output stream
            $handle = fopen('php://output', 'w');
            // Add CSV headers
            fputcsv($handle, ["id", "medical_case_id", "answer_id", "node_id", "value", "created_at", "updated_at"]);
            $case_answers = MedicalCaseAnswer::all();
            $case_answers->each(function ($case_answer) use (&$handle) {
                $c_answer = [
                    $case_answer->id,
                    $case_answer->medical_case_id,
                    $case_answer->answer_id,
                    $case_answer->node_id,
                    $case_answer->value,
                    $case_answer->created_at,
                    $case_answer->updated_at,
                ];
                fputcsv($handle, $c_answer);
            });
            // Close the output stream
            fclose($handle);
        };
        // build response headers so file downloads.
        $headers = ['Content-Type' => 'text/csv'];
        // return the response as a streamed response.
        for ($i = 0; $i < 7; $i++) {
            return response()->streamDownload($callback, 'medical_case_answers.csv', $headers);
        }
        // return response()->streamDownload($callback, 'medical_case_answers.csv', $headers);
    }
    public function answers()
    {
        self::checkGateAllows();
        return Excel::download(new AnswerExport, 'answers.csv');
    }
    public function diagnosisReferences()
    {
        self::checkGateAllows();
        return Excel::download(new DiagnosisReferenceExport, 'diagnosis_references.csv');
    }
    public function customDiagnoses()
    {
        self::checkGateAllows();
        return Excel::download(new CustomDiagnosisExport, 'custom_diagnoses.csv');
    }
    public function drugReferences()
    {
        self::checkGateAllows();
        return Excel::download(new DrugReferenceExport, 'drug references.csv');
    }
    public function additionalDrugs()
    {
        self::checkGateAllows();
        return Excel::download(new AdditionalDrugExport, 'additional_drugs.csv');
    }
    public function managementReferences()
    {
        self::checkGateAllows();
        return Excel::download(new ManagementReferenceExport, 'management_references.csv');
    }

    public function diagnoses()
    {
        self::checkGateAllows();
        return Excel::download(new DiagnosisExport, 'diagnoses.csv');
    }
    public function drugs()
    {
        self::checkGateAllows();
        return Excel::download(new DrugExport, 'drugs.csv');
    }
    public function formulations()
    {
        self::checkGateAllows();
        return Excel::download(new FormulationExport, 'formulations.csv');
    }
    public function managements()
    {
        self::checkGateAllows();
        return Excel::download(new ManagementExport, 'managements.csv');
    }
    public function nodes()
    {
        self::checkGateAllows();
        return Excel::download(new NodeExport, 'nodes.csv');
    }
    public function answer_types()
    {
        self::checkGateAllows();
        return Excel::download(new AnswerTypeExport, 'answer_types.csv');
    }
    public function algorithms()
    {
        self::checkGateAllows();
        return Excel::download(new AlgorithmExport, 'algorithms.csv');
    }
    public function algorithmVersions()
    {
        self::checkGateAllows();
        return Excel::download(new VersionExport, 'algorithm_versions.csv');
    }

    public function drugsSummary()
    {
        $drug_references = DrugReference::where('agreed', 1)->with([
            'diagnosisReference',
            'diagnosisReference.diagnoses',
            'diagnosisReference.medical_case',
            'drugs',
        ])->get();
        $drug_references->each(function ($drug_reference) {
            $drug_reference->case_id = ($drug_reference->diagnosisReference->medical_case)->local_medical_case_id;
            $drug_reference->formulation = $drug_reference->formulationSelected;
            $drug_reference->drug_label = $drug_reference->drugs->label;
            $drug_reference->diagnosis_label = $drug_reference->diagnosisReference->diagnoses->label;
        });
        return view('drugs.index')->with('drugs', $drug_references);
    }
    public function diagnosesSummary()
    {
        $diagnoses_references = DiagnosisReference::where('agreed', true)->with([
            'medical_case',
            'medical_case.patient',
            'medical_case.facility',
            'diagnoses',
        ])->get();
        $diagnoses_references->each(function ($diagnoses_reference) {
            $case = $diagnoses_reference->medical_case;
            $facility = $case->facility;

            $diagnoses_reference->local_medical_case_id = $case->local_medical_case_id;
            $diagnoses_reference->patient_id = $case->patient->id;
            $diagnoses_reference->local_patient_id = $case->patient->local_patient_id;
            $diagnoses_reference->facility_name = ($facility && $facility->facility_name) ? $diagnoses_reference->facility_name = $facility->facility_name : '';
            $diagnoses_reference->diagnosis_label = $diagnoses_reference->diagnoses->label;
        });
        return view('diagnoses.index')->with('diagnoses', $diagnoses_references);
    }
}
