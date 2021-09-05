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
      $date=Carbon::now()->format('Y_m_d');
      $zip_filename=$date.'.zip';
      $this->info(json_encode($date));
      // makeFlatCsv
      $zip_folder_name='flat_zip';
      $csv_folder_name='flat_files';
      if(! Storage::has($zip_folder_name)){
        Storage::makeDirectory($zip_folder_name);
      }
      $files = Storage::Files($zip_folder_name);
      $this->info(json_encode($zip_filename));
      $this->info(json_encode($files));
      $files= Storage::files($zip_folder_name);
      if(! Storage::has($zip_folder_name.'/'.$zip_filename)){
        $this->makeZip($zip_filename,$csv_folder_name,$zip_folder_name);
      }else{
        if(count($files) > 1){
          Storage::allFiles($zip_folder_name);
          Storage::delete($files);
          $this->makeZip($zip_filename,$csv_folder_name,$zip_folder_name);
        }
      }
    }

    public function makeZip($zip_filename,$csv_folder_name,$zip_folder_name){
      $this->info('making zip file '.$zip_filename);
        $case_answers=new MedicalCaseAnswer();
        $thingsArray=[];
        foreach($case_answers->makeFlatCsv() as $file_string){
          array_push($thingsArray,Storage::path($csv_folder_name).'/'.$file_string);
        }
        $path=Storage::path($zip_folder_name).'\\'.$zip_filename;
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zipper->make($path)->add($thingsArray);
        $zipper->close();
    }
}
