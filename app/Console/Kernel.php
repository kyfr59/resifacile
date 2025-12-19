<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 10 x 1mn - 5 login per 30mn per host
        $schedule->command('notification:get')->everyThirtyMinutes()->onOneServer();
        //$schedule->command('campaign:execute')->everyFiveMinutes()->onOneServer();

        $schedule->command('mail:send-proof')->everyThirtyMinutes()->onOneServer();
        $schedule->command('tracking:get')->dailyAt('8:00')->onOneServer();

        $schedule->command('subscription:invoices')->dailyAt('22:00')->onOneServer();
        $schedule->command('subscription:renew')->dailyAt('1:00');

        $schedule->command('horizon:snapshot')->everyTenMinutes()->onOneServer();

        $schedule->command('sitemap:generate')->daily()->onOneServer();

        $schedule->command('telescope:prune --hours=48')->daily();
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
