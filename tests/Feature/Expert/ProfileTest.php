<?php

use App\Models\User;
use App\Models\ExpertProfile;
use App\Enums\UserRole;
use Livewire\Volt\Volt;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('experts can access profile page', function () {
    $user = User::factory()->create(['role' => UserRole::EXPERT]);

    $this->actingAs($user)
        ->get(route('expert.profile'))
        ->assertOk();
});

test('non-experts cannot access profile page', function () {
    $user = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($user)
        ->get(route('expert.profile'))
        ->assertForbidden();
});

test('experts can update their profile', function () {
    $user = User::factory()->create(['role' => UserRole::EXPERT]);
    
    // Create initial profile
    ExpertProfile::create([
        'user_id' => $user->id,
        'bio' => 'Old bio',
    ]);

    Volt::actingAs($user)
        ->test('expert.edit-profile')
        ->set('bio', 'New bio')
        ->set('location', 'New York')
        ->set('hourly_rate', 100)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('expert_profiles', [
        'user_id' => $user->id,
        'bio' => 'New bio',
        'location' => 'New York',
        'hourly_rate' => 100,
    ]);
});
