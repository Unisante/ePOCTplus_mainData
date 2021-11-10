<?php

namespace App\Console\Commands;

use App\Diagnosis;
use App\DiagnosisReference;
use App\MedicalCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixDiagNullAgreedValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json:rebuild {dry-run=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will take every json and get their diagnoses values to update the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('dry-run') == 1) {
            $this->info('Dry Run');
        }
        $all_agreed_diags = [];
        $all_refused_diags = [];
        $json_success_files = Storage::files('json_success');

        foreach ($json_success_files as $json) {
            $filename = array_slice(explode('/', $json), -1)[0];
            $caseData = json_decode(Storage::get("json_success/$filename"), true);
            if ($caseData === null) {
                continue;
            }
            $medical_case_id = $caseData['id'];
            $medical_case = MedicalCase::where('local_medical_case_id', $medical_case_id);
            if ($medical_case->first() === null) {
                continue;
            }
            $medical_case = $medical_case->first();
            $diagnosesData = $caseData['diagnosis'];
            if ($diagnosesData === null) {
                continue;
            }
            $agreed_diags = $diagnosesData['agreed'];
            $refused_diags = $diagnosesData['refused'];
            if ($agreed_diags === null) {
                continue;
            }
            foreach ($agreed_diags as $diag) {
                $diag_id = $diag['id'] ?? $diag;
                $diagnoses = Diagnosis::where('medal_c_id', $diag_id)->get();
                if ($diagnoses === null) {
                    continue;
                }
                foreach ($diagnoses as $diagnose) {
                    $diag_ref = DiagnosisReference::where('medical_case_id', $medical_case->id)
                        ->where('diagnosis_id', $diagnose->id)
                        ->first();
                    if ($diag_ref === null) {
                        continue;
                    }
                    if ($diag_ref->agreed !== null) {
                        continue;
                    }
                    if ($this->argument('dry-run') == 0) {
                        $diag_ref->update([
                            'agreed' => true,
                        ]);
                        $this->info("Diagnoses reference $diag_ref->id ($diagnose->label) from medical case $medical_case->id updated to agreed");
                    }

                    if (!in_array($diag_ref->id, $all_agreed_diags)) {
                        $all_agreed_diags[] = $diag_ref->id;
                    }

                }
            }
            foreach ($refused_diags as $diag) {
                $diagnoses = Diagnosis::where('medal_c_id', $diag)->get();
                if ($diagnoses === null) {
                    continue;
                }
                foreach ($diagnoses as $diagnose) {
                    $diag_ref = DiagnosisReference::where('medical_case_id', $medical_case->id)
                        ->where('diagnosis_id', $diagnose->id)
                        ->first();
                    if ($diag_ref === null) {
                        continue;
                    }
                    if ($diag_ref->agreed !== null) {
                        continue;
                    }
                    if ($this->argument('dry-run') == 0) {
                        $diag_ref->update([
                            'agreed' => false,
                        ]);
                        $this->info("Diagnoses reference $diag_ref->id ($diagnose->label) from medical case $medical_case->id updated to refused");
                    }

                    if (!in_array($diag_ref->id, $all_refused_diags)) {
                        $all_refused_diags[] = $diag_ref->id;
                    }
                }
            }
        }
        if ($this->argument('dry-run') == 0) {
            $this->info(count($all_agreed_diags) . ' diagnoses updated to agreed');
            $this->info(count($all_refused_diags) . ' diagnoses updated to refused');
        } else {
            $this->info(count($all_agreed_diags) . ' diagnoses would have been updated to agreed');
            $this->info(count($all_refused_diags) . ' diagnoses would have been updated to refused');
        }
    }
}
