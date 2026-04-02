<?php

namespace Database\Factories;

use App\Enums\OfferStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'install_request_id' => InstallRequest::factory(),
            'expert_user_id' => User::factory()->state(['role' => UserRole::EXPERT]),
            'is_free' => true,
            'price_amount' => null,
            'currency' => 'USD',
            'message' => fake()->sentence(),
            'status' => OfferStatus::PENDING,
        ];
    }
}
