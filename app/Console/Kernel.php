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
        $schedule->command('order:expired')->everyMinute()->timezone('Asia/Jakarta');
        $schedule->command('order:repeat')->daily()->at('00:00')->timezone('Asia/Jakarta');
        $schedule->command('order-due-date:expired-mikrotik')->daily()->at("00:00")->timezone('Asia/Jakarta');
        $schedule->command('order-on-time-pay:expired-mikrotik')->daily()->at("00:00")->timezone('Asia/Jakarta');
        $schedule->command('sync:mikrotik-to-database')->daily()->at("00:00")->timezone('Asia/Jakarta');
        $schedule->command('product:empty-stock')->everyMinute()->timezone('Asia/Jakarta');
        $schedule->command('order:reminder-unpaid')->daily()->at("00:00")->timezone('Asia/Jakarta');
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
