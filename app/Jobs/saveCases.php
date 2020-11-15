<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\MedicalCase;
class saveCases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data=array();
    protected $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$path)
    {
        $this->data=$data;
        $this->path=$path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      dd("am here");
        MedicalCase::saveCases();
    }
}
