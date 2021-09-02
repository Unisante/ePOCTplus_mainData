<?php

namespace App\Console\Commands;

use App\Jobs\PushMedicalCase;
use App\MedicalCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MedicalCasesExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medicalCases:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
      $nbMedicalCase = 0;
      MedicalCase::where('redcap',false)->get()->each(function ($medicalCase) use (&$nbMedicalCase) {
        dispatch(new PushMedicalCase($medicalCase));
        ++$nbMedicalCase;
      });
      Log::info($nbMedicalCase . " medical case(s) dispatch for export");
    }
}
