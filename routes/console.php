<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('peminjaman:ingatkan-pengembalian')->dailyAt('08:00')->withoutOverlapping();
Schedule::command('peminjaman:tandai-terlambat')->dailyAt('00:05')->withoutOverlapping();
