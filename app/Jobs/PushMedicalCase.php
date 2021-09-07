<?php

namespace App\Jobs;

use App\MedicalCase;
use App\Services\RedCapApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushMedicalCase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var MedicalCase $medicalCase */
    protected $medicalCase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($medicalCase)
    {
      $this->medicalCase = $medicalCase;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RedCapApiService $redcapApiService)
    {
      try {
        $redcapApiService->exportMedicalCase($this->medicalCase);
      } catch (Exception $ex) {
        Log::error($ex->getMessage());
      }
      Log::info('--> medical case exported');
    }
}
