<?php

namespace App\Console\Commands;

use App\Jobs\ExportFlat;
use App\Jobs\ExportSeparated;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StartExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command launch the execution of both exports';

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
        $this->info("Export manually started");
        Log::info("Export manually started");
        ExportFlat::withChain([
            new ExportSeparated,
        ])->onQueue("low")->dispatch();
    }
}
