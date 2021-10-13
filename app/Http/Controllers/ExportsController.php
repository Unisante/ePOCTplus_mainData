<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\DiagnosisReference;
use App\Drug;
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
use App\Formulation;
use App\MedicalCase;
use App\MedicalCaseAnswer;
use App\Patient;
use App\Services\ExportCsvFlat;
use App\Services\ExportCsvSeparate;
use App\User;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ExportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportZipByDate(Request $request)
    {
        $export_completion = 0;
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '300');

        if (Patient::all()->count() == 0) {
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
            return back()->withErrors("Invalid date interval.");
        }
        if ($toDate > Carbon::now() || $toDate > Carbon::now()) {
            return back()->withErrors("Date cannot be in the future.");
        }

        $extract_file_name = Config::get('csv.public_extract_name');
        $file_from_public = base_path() . '/public/' . $extract_file_name . '.zip';
        // generate the data file.
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($extract_file_name . '.zip');
        // check export mode
        if (Arr::exists($request->input(), 'DownloadFlat')) {
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
            ])->chunk(100, function ($medical_case, $key) use ($fromDate, $toDate) {
                $csv_export = new ExportCsvFlat($medical_case, $fromDate, $toDate);
                $csv_export->export($key);
            });
            $zipper->add(public_path(Config::get('csv.flat.folder') . 'answers.csv'));

        } else if (Arr::exists($request->input(), 'DownloadSeparate')) {
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
            ])->chunk(100, function ($medical_case, $key) use ($fromDate, $toDate) {
                $csv_export = new ExportCsvSeparate($medical_case, $fromDate, $toDate);
                $csv_export->export($key);
            });
            $zipper->add(public_path(Config::get('csv.folder_separated')));

        } else {
            return back()->withErrors("Something went wrong.");
        }

        $zipper->close();

        // download the data file.
        $from_date_str = $fromDate->format('Y-m-d');
        $to_date_str = $toDate->sub(new DateInterval('P1D'))->format('Y-m-d');

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
        exit;
    }

    public function selectDate()
    {
        if (Patient::count() == 0) {
            return back()->withErrors("We Currently Do not have any records in the database");
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

        $data = array(
            'currentUser' => Auth::user(),
            'userCount' => User::count(),
            'mdCases' => MedicalCase::count(),
            'oldest_date' => date('Y-m-d', min($date_array)),
            'newest_date' => date('Y-m-d', max($date_array)),
            'patientCount' => Patient::count(),
        );

        return view('exports.index')->with($data);
    }
    public function Patients()
    {
        return Excel::download(new PatientExport, 'patients.csv');
    }
    public function cases()
    {
        return Excel::download(new Medical_CaseExport, 'medical_cases.csv');
    }
    public function casesAnswers()
    {
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '300');
        return Excel::download(new MedicalCaseAnswerExport, 'medical_case_answers.csv');
    }
    public function casesAnswers2()
    {
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
        return Excel::download(new AnswerExport, 'answers.csv');
    }
    public function diagnosisReferences()
    {
        return Excel::download(new DiagnosisReferenceExport, 'diagnosis_references.csv');
    }
    public function customDiagnoses()
    {
        return Excel::download(new CustomDiagnosisExport, 'custom_diagnoses.csv');
    }
    public function drugReferences()
    {
        return Excel::download(new DrugReferenceExport, 'drug references.csv');
    }
    public function additionalDrugs()
    {
        return Excel::download(new AdditionalDrugExport, 'additional_drugs.csv');
    }
    public function managementReferences()
    {
        return Excel::download(new ManagementReferenceExport, 'management_references.csv');
    }

    public function diagnoses()
    {
        return Excel::download(new DiagnosisExport, 'diagnoses.csv');
    }
    public function drugs()
    {
        return Excel::download(new DrugExport, 'drugs.csv');
    }
    public function formulations()
    {
        return Excel::download(new FormulationExport, 'formulations.csv');
    }
    public function managements()
    {
        return Excel::download(new ManagementExport, 'managements.csv');
    }
    public function nodes()
    {
        return Excel::download(new NodeExport, 'nodes.csv');
    }
    public function answer_types()
    {
        return Excel::download(new AnswerTypeExport, 'answer_types.csv');
    }
    public function algorithms()
    {
        return Excel::download(new AlgorithmExport, 'algorithms.csv');
    }
    public function algorithmVersions()
    {
        return Excel::download(new VersionExport, 'algorithm_versions.csv');
    }

    public function drugsSummary()
    {
        $drug_ref0 = DrugReference::where('agreed', 1)->get();
        $drug_ref0->each(function ($drug_ref) {
            $drug = Drug::find($drug_ref->drug_id);
            $diagnosis_ref = DiagnosisReference::find($drug_ref->diagnosis_id);
            $drug_ref->case_id = MedicalCase::find($diagnosis_ref->medical_case_id)->local_medical_case_id;
            $diagnosis = Diagnosis::find($diagnosis_ref->diagnosis_id);
            $formulations = Formulation::where('drug_id', $drug->id)->get();
            $drug_ref->formulation = $formulations->get($drug_ref->formulationSelected);
            $drug_ref->drug_label = $drug->label;
            $drug_ref->diagnosis_label = $diagnosis->label;
        });
        return view('drugs.index')->with('drugs', $drug_ref0);
    }
    public function diagnosesSummary()
    {
        $diagnoses_ref0 = DiagnosisReference::where('agreed', true)->get();
        $diagnoses_ref0->each(function ($d_f) {
            $case = MedicalCase::find($d_f->medical_case_id);
            $d_f->local_medical_case_id = $case->local_medical_case_id;
            $d_f->patient_id = $case->patient->id;
            $d_f->local_patient_id = $case->patient->local_patient_id;
            // $d_f->facility_name=$case->facility->facility_name;
            if ($case->facility && $case->facility->facility_name) {
                $d_f->facility_name = $case->facility->facility_name;
            } else {
                $case->facility_name = '';
            }
            $d_f->diagnosis_label = Diagnosis::find($d_f->diagnosis_id)->label;
        });
        return view('diagnoses.index')->with('diagnoses', $diagnoses_ref0);
    }
}
