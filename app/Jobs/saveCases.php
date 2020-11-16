<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;

use Madzipper;
use File;
class saveCases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file=$file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      error_log('job is being performed');
      $study_id='Test';
      error_log($study_id);
      $isEligible=true;
      error_log($isEligible);
      $unparsed_path = base_path().'/storage/medicalCases/unparsed_medical_cases';
      $parsed_path = base_path().'/storage/medicalCases/parsed_medical_cases';
      $consent_path = base_path().'/storage/consentFiles';
      error_log($consent_path);
      Madzipper::make($this->file)->extractTo($unparsed_path);
      $files = File::allFiles($unparsed_path);
      foreach($files as $path){
        error_log('each file');
      }
      error_log("job done");
      MedicalCase::syncMedicalCases($this->file);
    }
}
