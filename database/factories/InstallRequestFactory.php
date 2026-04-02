<?php

namespace Database\Factories;

use App\Enums\InstallRequestStatus;
use App\Models\InstallRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstallRequest>
 */
class InstallRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraph(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'region' => null,
            'hardware_notes' => fake()->optional()->sentence(),
            'status' => InstallRequestStatus::OPEN,
            'accepted_offer_id' => null,
        ];
    }

    public function matched(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InstallRequestStatus::MATCHED,
        ]);
    }
}
