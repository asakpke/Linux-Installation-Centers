<?php

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Enums\UserRole;
use App\Models\ExpertProfile;
use App\Models\InstallRequest;
use App\Models\InstallRequestMessage;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function createMatchedInstallRequest(): array
{
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $expert = User::factory()->expert()->create();
    ExpertProfile::create([
        'user_id' => $expert->id,
        'bio' => 'Linux professional',
        'city' => 'Lahore',
        'country' => 'Pakistan',
    ]);

    $installRequest = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'city' => 'Lahore',
        'country' => 'Pakistan',
        'status' => InstallRequestStatus::OPEN,
    ]);

    $offer = Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expert->id,
        'is_free' => true,
        'message' => 'Saturday',
        'status' => OfferStatus::PENDING,
        'currency' => 'USD',
    ]);

    $installRequest->acceptOffer($offer);
    $installRequest->refresh();

    return [$seeker, $expert, $installRequest];
}

test('seeker and assigned expert can mount message thread on matched request', function () {
    [$seeker, $expert, $installRequest] = createMatchedInstallRequest();

    Livewire::actingAs($seeker)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->assertOk();

    Livewire::actingAs($expert)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->assertOk();
});

test('non participant cannot mount message thread', function () {
    [$seeker, $expert, $installRequest] = createMatchedInstallRequest();
    unset($seeker, $expert);

    $other = User::factory()->create(['role' => UserRole::USER]);

    Livewire::actingAs($other)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->assertForbidden();
});

test('expert with rejected offer cannot mount message thread', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $expertAccepted = User::factory()->expert()->create();
    $expertRejected = User::factory()->expert()->create();

    foreach ([$expertAccepted, $expertRejected] as $expert) {
        ExpertProfile::create([
            'user_id' => $expert->id,
            'bio' => 'Pro',
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

    Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expertAccepted->id,
        'is_free' => true,
        'message' => 'A',
        'status' => OfferStatus::PENDING,
        'currency' => 'USD',
    ]);

    Offer::create([
        'install_request_id' => $installRequest->id,
        'expert_user_id' => $expertRejected->id,
        'is_free' => true,
        'message' => 'B',
        'status' => OfferStatus::PENDING,
        'currency' => 'USD',
    ]);

    $accepted = Offer::query()
        ->where('install_request_id', $installRequest->id)
        ->where('expert_user_id', $expertAccepted->id)
        ->first();
    $installRequest->acceptOffer($accepted);
    $installRequest->refresh();

    Livewire::actingAs($expertRejected)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->assertForbidden();
});

test('seeker and expert can exchange messages while matched', function () {
    [$seeker, $expert, $installRequest] = createMatchedInstallRequest();

    Livewire::actingAs($seeker)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->set('body', 'Can we meet at 3pm?')
        ->call('postMessage')
        ->assertHasNoErrors();

    expect(InstallRequestMessage::query()->count())->toBe(1);

    Livewire::actingAs($expert)
        ->test('install-request.message-thread', ['installRequest' => $installRequest->refresh()])
        ->set('body', 'Yes, works for me.')
        ->call('postMessage')
        ->assertHasNoErrors();

    expect(InstallRequestMessage::query()->count())->toBe(2);
    expect($installRequest->refresh()->messages()->pluck('body')->all())->toBe([
        'Can we meet at 3pm?',
        'Yes, works for me.',
    ]);
});

test('posting is forbidden when request is closed', function () {
    [$seeker, $expert, $installRequest] = createMatchedInstallRequest();
    unset($expert);

    $installRequest->update(['status' => InstallRequestStatus::CLOSED]);

    Livewire::actingAs($seeker)
        ->test('install-request.message-thread', ['installRequest' => $installRequest->refresh()])
        ->set('body', 'Late message')
        ->call('postMessage')
        ->assertForbidden();
});

test('admin can mount message thread when request has accepted offer', function () {
    [$seeker, $expert, $installRequest] = createMatchedInstallRequest();
    unset($seeker, $expert);

    $admin = User::factory()->admin()->create();

    Livewire::actingAs($admin)
        ->test('install-request.message-thread', ['installRequest' => $installRequest])
        ->assertOk()
        ->assertSee(__('Admins can read this thread for support; only the seeker and assigned expert can post.'));
});
