<?php

namespace App\Console;

use App\Console\Commands\SendEmailVerificationReminderCommand;
use App\Console\Commands\SendNewsletterCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('inspire')->sendOutputTo(storage_path('inspire.log'))->evenInMaintenanceMode()->everyMinute();

        $schedule->call(function () {
            echo 'Hola mundo';
        })->everyMinute();

        $schedule->command(SendNewsletterCommand::class)->onOneServer()->withoutOverlapping()->mondays();
        $schedule->command(SendEmailVerificationReminderCommand::class)->onOneServer()->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
