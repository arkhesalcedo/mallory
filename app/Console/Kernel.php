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
            
            $run->fetch('US', \Carbon\Carbon::today('UTC')->subDays(2)->toDateTimeString());
        })->twiceDaily(0, 1);

        $schedule->call(function() {
            $run = new \App\Customer;
            
            $run->fetch('CA', \Carbon\Carbon::today('UTC')->subDays(2)->toDateTimeString());
        })->twiceDaily(2, 3);

        $schedule->call(function() {
            $run = new \App\Customer;
            
            $run->fetch('UK', \Carbon\Carbon::today('UTC')->subDays(2)->toDateTimeString());
        })->twiceDaily(4, 5);
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
