<?php

use App\Models\User;
use Illuminate\Support\Facades\Cache;

it('shows the search page', function () {
    $this->get('/')
        ->assertOk()
        ->assertViewIs('search');
});

it('returns Not Registered if user not found', function () {
    Cache::shouldReceive('remember')->andReturn(['status' => 'Not registered']);

    $this->post(route('search.result'), [
        'nid' => '123456',
    ])->assertOk()
        ->assertViewIs('search')
        ->assertSee('Not registered');
});

it('returns Vaccinated if vaccination date is past', function () {
    $user = User::factory()->vaccinated()->create();

    Cache::shouldReceive('remember')->andReturn(['status' => 'Vaccinated']);

    $this->post(route('search.result'), [
        'nid' => $user->nid,
    ])->assertOk()
        ->assertViewIs('search')
        ->assertSee('Vaccinated');
});

it('returns Scheduled if vaccination date is future', function () {
    $user = User::factory()->scheduled()->create();

    $this->withoutExceptionHandling();

    Cache::shouldReceive('remember')
        ->andReturn([
            'status' => 'Scheduled',
            'date' => $user?->scheduled_date,
        ]);

    $this->post(route('search.result'), [
        'nid' => $user->nid,
    ])->assertOk()
        ->assertViewIs('search')
        ->assertSee('Scheduled')
        ->assertSee($user?->scheduled_date->format('d M, Y'));

});

it('returns Not Scheduled if no vaccination date is set', function () {
    $user = User::factory()->create();

    Cache::shouldReceive('remember')->andReturn(['status' => 'Not scheduled']);

    $this->post(route('search.result'), [
        'nid' => $user->nid,
    ])->assertOk()
        ->assertViewIs('search')
        ->assertSee('Not scheduled');
});
