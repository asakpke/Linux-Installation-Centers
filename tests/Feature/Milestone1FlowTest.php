<?php

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Enums\UserRole;
use App\Models\ExpertProfile;
use App\Models\InstallRequest;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('inactive users cannot log in', function () {
    $user = User::factory()->inactive()->create();

    Livewire::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors('email');

    $this->assertGuest();
});

test('accepting an offer matches request and rejects other pending offers', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $expertA = User::factory()->expert()->create();
    $expertB = User::factory()->expert()->create();

    foreach ([$expertA, $expertB] as $expert) {
        ExpertProfile::create([
            'user_id' => $expert->id,
            'bio' => 'Linux professional',
            'city' => 'Lahore',
            'country' => 'Pakistan',
        ]);
    }

    $installRequest = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'city' => 'Lahore',
        'country' => 'Pakistan',
        'status' => InstallRequestStatus::OPEN,
    ]);

    $offerA = Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expertA->id,
        'is_free' => true,
        'message' => 'Saturday',
        'status' => OfferStatus::PENDING,
        'currency' => 'USD',
    ]);

    $offerB = Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expertB->id,
        'is_free' => false,
        'price_amount' => 50,
        'message' => 'Paid slot',
        'status' => OfferStatus::PENDING,
        'currency' => 'USD',
    ]);

    $installRequest->acceptOffer($offerA);

    $installRequest->refresh();
    $offerA->refresh();
    $offerB->refresh();

    expect($installRequest->status)->toBe(InstallRequestStatus::MATCHED);
    expect($installRequest->accepted_offer_id)->toBe($offerA->id);
    expect($offerA->status)->toBe(OfferStatus::ACCEPTED);
    expect($offerB->status)->toBe(OfferStatus::REJECTED);
});

test('seeker can open requests index when verified', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);

    $this->actingAs($seeker)
        ->get(route('requests.index'))
        ->assertOk();
});

test('admin can view install requests index', function () {
    $admin = User::factory()->admin()->create();
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    InstallRequest::factory()->create(['user_id' => $seeker->id]);

    $this->actingAs($admin)
        ->get(route('admin.requests.index'))
        ->assertOk();
});

test('expert can view assignments page', function () {
    $expert = User::factory()->expert()->create();

    $this->actingAs($expert)
        ->get(route('expert.assignments'))
        ->assertOk();
});

test('seeker may complete policy only when request is matched', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $matched = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'status' => InstallRequestStatus::MATCHED,
    ]);
    $open = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'status' => InstallRequestStatus::OPEN,
    ]);

    expect($seeker->can('complete', $matched))->toBeTrue();
    expect($seeker->can('complete', $open))->toBeFalse();
});

test('marking matched request closed moves expert assignment to completed', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $expert = User::factory()->expert()->create();
    ExpertProfile::create([
        'user_id' => $expert->id,
        'bio' => 'Pro',
        'city' => 'Lahore',
        'country' => 'Pakistan',
    ]);

    $installRequest = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'city' => 'Lahore',
        'country' => 'Pakistan',
        'status' => InstallRequestStatus::MATCHED,
    ]);

    $offer = Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expert->id,
        'is_free' => true,
        'message' => 'Ok',
        'status' => OfferStatus::ACCEPTED,
        'currency' => 'USD',
    ]);

    $installRequest->update(['accepted_offer_id' => $offer->id]);

    $installRequest->update(['status' => InstallRequestStatus::CLOSED]);
    $installRequest->refresh();

    expect($installRequest->status)->toBe(InstallRequestStatus::CLOSED);

    $this->actingAs($expert)
        ->get(route('expert.assignments'))
        ->assertOk()
        ->assertSee($installRequest->title, escape: false);
});
