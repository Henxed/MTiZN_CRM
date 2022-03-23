<?php

namespace App\Console;

use App\Models\Areas;
use App\Models\AreasLog;
use App\Models\Regist;
use App\Models\AreasExtra;
use Carbon\Carbon;
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
        $schedule->call(function () {
            Regist::whereDate('term', Carbon::today())
              ->update(['status_id' => 103]);
        })->daily()->description('Смена статуса на "Истёк срок" (103) каждый день');

        $schedule->call(function () {
            $area = Areas::leftJoin('areas_extra', function($join) {
                $join->on('areas.id', '=', 'areas_extra.area_id');
            })->select(['areas.id', 'population', 'amw', 'subject', 'contract', 'lvl', 'unemployed', 'unemployed_percent', 'vacancy', 'areas_extra.tension', 'employed', 'cripple', 'cripple_worked', 'commissions_c', 'commissions_t', 'commissions', 'in_employment', 'school', 'vvuz', 'ssuz', 'detdom', 'nou', 'ur',  'jobs'])->get();

            AreasLog::setLog(null, json_encode($area), 'STATS_MONTH');
        })->monthly()->description('Статистика месяца по показателям региона');
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
