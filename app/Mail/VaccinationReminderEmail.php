<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** @codeCoverageIgnore */
class VaccinationReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function build()
    {
        return $this->subject('Vaccination Reminder')
            ->view('emails.vaccination_reminder')
            ->with([
                'name' => $this->user->name,
                'scheduled_date' => $this->user->scheduled_date->toFormattedDateString(),
                'center' => $this->user->vaccineCenter->name,
            ]);
    }
}
