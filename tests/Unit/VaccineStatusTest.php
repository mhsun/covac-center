<?php

use App\Enums\VaccineStatus;

it('returns correct string value for Registered status', function () {
    expect(VaccineStatus::Registered->value)->toBe('registered');
});

it('returns correct string value for Scheduled status', function () {
    expect(VaccineStatus::Scheduled->value)->toBe('scheduled');
});

it('returns correct string value for NotScheduled status', function () {
    expect(VaccineStatus::NotScheduled->value)->toBe('not-scheduled');
});

it('returns correct string value for Vaccinated status', function () {
    expect(VaccineStatus::Vaccinated->value)->toBe('vaccinated');
});
