<?php

namespace App\Console\Commands;

use App\MedicalCase;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\MedicalCaseAnswer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class MakeFlatZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flatZip:make';

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

      $date=Carbon::now()->setTimezone('Africa/Nairobi');
      $date->format('Y_m_d');
      $this->info(json_encode($date));
      $case_answers=new MedicalCaseAnswer();
      $case_answers->makeFlatCsv();
    }
}
