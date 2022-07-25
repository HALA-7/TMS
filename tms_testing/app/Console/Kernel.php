<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // i add it
    protected $commands=[Commands\DailyTest::class];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('test:daily')
                 ->everyMinute()
                 ->appendOutputTo('scheduler.log');
    }

    /*
     * define a default time zone
     *
     * public function scheduleTimezone()
     * {
     *    return 'America/Chicago';
     * }
     *
     */

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
