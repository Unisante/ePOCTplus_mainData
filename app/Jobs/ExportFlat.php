<?php

namespace App\Jobs;

use App\MedicalCase;
use App\Services\ExportCsvFlat;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ExportFlat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Starting flat export');
        $export_file = storage_path('app/export/export_flat.zip');
        $toDate = new DateTime();
        if (File::exists($export_file)) {
            $lastmodified_file = File::lastModified($export_file);
            $lastmodified = DateTime::createFromFormat("U", $lastmodified_file);
            if ($toDate->diff($lastmodified)->h < 5 && $toDate->diff($lastmodified)->d < 1) {
                Log::info('Flat export already done in the last 5 hours, skipping');
                return;
            }
        }

        ini_set('memory_limit', '4096M');
        $extract_file_name = Config::get('csv.public_extract_name_flat');
        $file_from_public = storage_path('app/export/' . $extract_file_name . '.zip');
        $fromDate = new DateTime('2020-01-01');
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($file_from_public);

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
        $zipper->add(storage_path('app/export/' . Config::get('csv.flat.folder') . 'answers.csv'));
        $zipper->close();
        File::deleteDirectory(storage_path('app/export/' . Config::get('csv.flat.folder')));
        Log::info('Flat export done');

    }
}
