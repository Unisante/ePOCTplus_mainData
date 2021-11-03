<?php

namespace App\Jobs;

use App\MedicalCase;
use App\Services\ExportCsvSeparate;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ExportSeparated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '4096M');

        $extract_file_name = Config::get('csv.public_extract_name_separated');
        $file_from_public = storage_path('app/export/' . $extract_file_name . '.zip');
        $fromDate = new DateTime('2020-01-01');
        $toDate = new DateTime();
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($file_from_public);

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
        $zipper->add(storage_path('app/export/' . Config::get('csv.folder_separated')));
        $zipper->close();
        File::deleteDirectory(storage_path('app/export/' . Config::get('csv.folder_separated')));

    }
}
