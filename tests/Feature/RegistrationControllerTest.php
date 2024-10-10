<?php

use App\Events\UserRegistered;
use App\Http\Controllers\RegistrationController;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

beforeEach(function () {
    VaccineCenter::factory()->count(3)->create();
});

it('displays the registration form with vaccine centers', function () {
    $response = (new RegistrationController)->create();

    expect($response->getData()['centers'])->toHaveCount(3);
});

it('registers a user successfully', function () {
    Event::fake();

    expect(User::count())->toBe(0);

    $this->post('/register', [
        'name' => fake()->name,
        'email' => fake()->safeEmail(),
        'nid' => fake()->unique()->numerify('#################'),
        'vaccine_center_id' => VaccineCenter::inRandomOrder()->first()->id,
    ]);

    Event::assertDispatched(UserRegistered::class);

    expect(User::count())->toBe(1);
});

it('fails to register a user with invalid data', function () {
    expect(User::count())->toBe(0);

    $this->post('/register', [
        'name' => '',
        'email' => fake()->safeEmail(),
        'nid' => '',
        'vaccine_center_id' => VaccineCenter::inRandomOrder()->first()->id,
    ]);

    expect(User::count())->toBe(0);
});

it('restricts user to be registered with same email', function () {
    $user = User::factory()->create();

    expect(User::count())->toBe(1);

    $this->post('/register', [
        'name' => fake()->name,
        'email' => $user->email,
        'nid' => fake()->unique()->numerify('#################'),
        'vaccine_center_id' => VaccineCenter::inRandomOrder()->first()->id,
    ]);

    expect(User::count())->toBe(1);
});

it('restricts user to be registered with same NID', function () {
    $user = User::factory()->create();

    expect(User::count())->toBe(1);

    $this->post('/register', [
        'name' => fake()->name,
        'email' => fake()->safeEmail(),
        'nid' => $user->nid,
        'vaccine_center_id' => VaccineCenter::inRandomOrder()->first()->id,
    ]);

    expect(User::count())->toBe(1);
});
