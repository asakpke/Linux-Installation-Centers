<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user with the 'expert' role can access the expert dashboard.
     */
    public function test_expert_user_can_access_expert_dashboard(): void
    {
        $expertUser = User::factory()->create([
            'role' => UserRole::EXPERT,
        ]);

        $response = $this->actingAs($expertUser)->get(route('expert.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText(__("You're logged in as an expert!"));
    }

    /**
     * Test that a user without the 'expert' role is forbidden from accessing the expert dashboard.
     */
    public function test_non_expert_user_cannot_access_expert_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'role' => UserRole::USER,
        ]);

        $response = $this->actingAs($regularUser)->get(route('expert.dashboard'));

        $response->assertStatus(403);
    }

    /**
     * Test that a guest is redirected to the login page.
     */
    public function test_guest_cannot_access_expert_dashboard(): void
    {
        $response = $this->get(route('expert.dashboard'));

        $response->assertRedirect(route('login'));
    }
}