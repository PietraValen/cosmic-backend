<?php

use Illuminate\Support\Facades\Schedule;

/*
Adicionar no cron do server
* * * * * php /caminho/do/projeto/artisan schedule:run >> /dev/null 2>&1
*/

Schedule::command('gwosc:sync-events')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/gwosc-events.log'));

Schedule::command('gwosc:sync-catalogs')
    ->weeklyOn(1, '05:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/gwosc-catalogs.log'));

Schedule::command('gwosc:update-stats')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/gwosc-stats.log'));