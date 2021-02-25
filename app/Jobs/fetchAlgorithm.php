<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Algorithm;
use Illuminate\Http\Request;
class fetchAlgorithm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agId;
    protected $verId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    // public function __construct(Int $agId,Int $verId)
    // {
    //     $this->agId=$agId;
    //     $this->verId=$verId;
    // }
    public function __construct($verId)
    {
        $this->verId=$verId;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        error_log('job is being performed');
        // dd($this->agId);

        // return $this->data;
        // dd($this->request);
        Algorithm::ifOrExists(array(
          "algorithm_id"=>$this->agId,
          "version_id"=>$this->verId
        ));
    }
}
