<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('sends reminder emails to users scheduled for tomorrow', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'scheduled_date' => \Carbon\Carbon::tomorrow(),
    ]);

    Notification::fake();

    $this->artisan('vaccination:send-reminders');

    Notification::assertSentTo($user, \App\Notifications\SendVaccinationReminder::class);
});

it('does not send reminder emails to users not scheduled for tomorrow', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'scheduled_date' => \Carbon\Carbon::today(),
    ]);

    Notification::fake();

    $this->artisan('vaccination:send-reminders');

    Notification::assertNothingSentTo($user);
});
