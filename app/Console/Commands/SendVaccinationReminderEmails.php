<?php

namespace App\Console\Commands;

use App\Mail\VaccinationReminderEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVaccinationReminderEmails extends Command
{
    protected $signature = 'vaccination:send-reminders';

    protected $description = 'Send vaccination reminder emails to users at 9 PM the night before their scheduled vaccination date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::with('vaccineCenter')
            ->whereDate('scheduled_date', Carbon::tomorrow())
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    Mail::to($user->email)->send(new VaccinationReminderEmail($user));
                }
            });
    }
}
