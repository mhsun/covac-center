<?php

namespace Database\Factories;

use App\Enums\VaccineStatus;
use App\Models\VaccineCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'nid' => fake()->unique()->numberBetween(1000000000000, 9999999999999),
            'vaccine_center_id' => VaccineCenter::factory(),
        ];
    }

    public function vaccinated(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_date' => now()->subDay(),
            'status' => VaccineStatus::Vaccinated->value,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_date' => now()->addDay(),
            'status' => VaccineStatus::Scheduled->value,
        ]);
    }
}
