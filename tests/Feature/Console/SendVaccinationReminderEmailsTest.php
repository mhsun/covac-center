<?php

use App\Mail\VaccinationReminderEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('sends reminder emails to users scheduled for tomorrow', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'scheduled_date' => \Carbon\Carbon::tomorrow(),
    ]);

    Mail::fake();

    $this->artisan('vaccination:send-reminders');

    Mail::assertSent(VaccinationReminderEmail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

it('does not send reminder emails to users not scheduled for tomorrow', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'scheduled_date' => \Carbon\Carbon::today(),
    ]);

    Mail::fake();

    $this->artisan('vaccination:send-reminders');

    Mail::assertNotSent(VaccinationReminderEmail::class);
});
