<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Renewals\ReminderEngineService;
use Illuminate\Console\Command;

class EvaluateRemindersCommand extends Command
{
    protected $signature = 'reminders:evaluate';
    protected $description = 'Evaluates reminder rules and dispatches notification jobs idempotently.';

    public function handle(ReminderEngineService $reminderEngineService): int
    {
        $this->info('Evaluating reminders...');

        $reminderEngineService->evaluateAndDispatch();

        $this->info('Reminder evaluation complete. Jobs dispatched to queue.');

        return Command::SUCCESS;
    }
}
