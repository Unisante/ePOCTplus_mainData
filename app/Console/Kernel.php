<?php

namespace App\Console;

use App\Jobs\RedcapPush;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\StartExport',
        '\App\Console\Commands\UpdateVersions',
        '\App\Console\Commands\UpdateHealthFacilitiesAlgorithms',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // timci project does not use redcap
        empty(Config::get('redcap.identifiers.api_url_followup')) ? null : $schedule->job(new RedcapPush())->everyThirtyMinutes()->withoutOverlapping(10);
        $schedule->command('export:start')->dailyAt('03:20');

        $schedule->command('update:versions')->hourly()->withoutOverlapping(10);
        $schedule->command("HealthFacilitiesAlgo:update")->hourly()->withoutOverlapping(10);
        $schedule->command('passport:purge')->hourly()->withoutOverlapping(10);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
