<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendVaccinationReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendVaccinationReminderEmails extends Command
{
    protected $signature = 'vaccination:send-reminders';

    protected $description = 'Send vaccination reminder emails to users at 9 PM the day before their scheduled appointment';

    public function handle()
    {
        User::with('vaccineCenter')
            ->whereDate('scheduled_date', Carbon::tomorrow())
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    /** @var User $user */
                    $user->notify(new SendVaccinationReminder);
                }
            });
    }
}
