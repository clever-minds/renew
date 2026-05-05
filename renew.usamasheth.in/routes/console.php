<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes & Scheduler
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// 1. Process Renewals (Runs daily at Midnight)
// This evaluates all subscriptions and generates invoices.
Schedule::command('renewals:process')->dailyAt('00:01')->withoutOverlapping();

// 2. Evaluate Reminders (Runs daily at 1:00 AM)
// Evaluates rules and dispatches idempotent jobs to send emails/WhatsApp.
Schedule::command('reminders:evaluate')->dailyAt('01:00')->withoutOverlapping();

// 3. Shared Hosting Queue Worker Fallback
// Since daemon processes (Supervisor) are often killed on shared hosting, 
// this runs the worker, processes the queue, and shuts down safely.
Schedule::command('queue:work --stop-when-empty --tries=3 --timeout=60')->everyFiveMinutes()->withoutOverlapping();

// 4. Recalculate Dashboard Stats (Runs daily at 2:00 AM)
// Caches heavy MRR/Stats queries to keep the SaaS UI fast.
Schedule::call(function () {
    $tenants = \Illuminate\Support\Facades\DB::table('tenants')->where('status', 'active')->pluck('id');
    foreach ($tenants as $tenantId) {
        \App\Jobs\RecalculateDashboardStatsJob::dispatch((int) $tenantId);
    }
})->name('recalculate-dashboard-stats')->dailyAt('02:00')->withoutOverlapping();
