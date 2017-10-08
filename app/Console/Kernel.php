<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            $run = new \App\Customer;
            
            $run->fetch('US');
        })->cron('*/3 * * * *');

        $schedule->call(function() {
            $run = new \App\Customer;
            
            $run->fetch('CA');
        })->cron('*/4 * * * *');

        $schedule->call(function() {
            $run = new \App\Customer;
            
            $run->fetch('UK');
        })->cron('*/5 * * * *');
    }

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
