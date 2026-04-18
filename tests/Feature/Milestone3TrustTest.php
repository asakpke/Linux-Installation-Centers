<?php

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Enums\ReportCategory;
use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Models\ExpertProfile;
use App\Models\InstallRequest;
use App\Models\Offer;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeClosedMatchedJob(): array
{
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
        'status' => InstallRequestStatus::CLOSED,
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

    return [$seeker, $expert, $installRequest->fresh()];
}

test('seeker can submit a review on a closed matched job', function () {
    [$seeker, $expert, $installRequest] = makeClosedMatchedJob();

    Livewire::actingAs($seeker)
        ->test('install-request.post-review', ['installRequest' => $installRequest])
        ->set('rating', 5)
        ->set('comment', 'Great help.')
        ->call('submitReview')
        ->assertHasNoErrors();

    expect(Review::query()->count())->toBe(1);
    $r = Review::first();
    expect($r->reviewer_id)->toBe($seeker->id)
        ->and($r->reviewee_id)->toBe($expert->id)
        ->and($r->rating)->toBe(5);
});

test('seeker cannot submit a second review for the same job', function () {
    [$seeker, $expert, $installRequest] = makeClosedMatchedJob();

    Review::create([
        'install_request_id' => $installRequest->id,
        'reviewer_id' => $seeker->id,
        'reviewee_id' => $expert->id,
        'rating' => 4,
        'comment' => null,
    ]);

    expect($seeker->can('createReview', $installRequest))->toBeFalse();
});

test('public profile returns 404 when disabled', function () {
    $user = User::factory()->create([
        'public_profile_enabled' => false,
        'public_slug' => 'hidden-user',
    ]);

    $this->get(route('profiles.show', ['public_slug' => 'hidden-user']))
        ->assertNotFound();
});

test('public profile returns 200 when enabled with slug', function () {
    $user = User::factory()->expert()->create([
        'public_profile_enabled' => true,
        'public_slug' => 'public-expert',
    ]);
    ExpertProfile::create([
        'user_id' => $user->id,
        'bio' => 'Linux pro',
        'city' => 'Lahore',
        'country' => 'Pakistan',
    ]);

    $this->get(route('profiles.show', ['public_slug' => 'public-expert']))
        ->assertOk()
        ->assertSee('Linux pro', escape: false);
});

test('expert cannot view spam install request', function () {
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
        'status' => InstallRequestStatus::SPAM,
    ]);

    $this->actingAs($expert)
        ->get(route('expert.requests.show', $installRequest))
        ->assertForbidden();
});

test('seeker can view their own spam install request', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $installRequest = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'status' => InstallRequestStatus::SPAM,
    ]);

    $this->actingAs($seeker)
        ->get(route('requests.show', $installRequest))
        ->assertOk();
});

test('authenticated user can report an install request they own', function () {
    $seeker = User::factory()->create(['role' => UserRole::USER]);
    $installRequest = InstallRequest::factory()->create([
        'user_id' => $seeker->id,
        'status' => InstallRequestStatus::OPEN,
    ]);

    Livewire::actingAs($seeker)
        ->test('install-request.report-install-request', ['installRequest' => $installRequest])
        ->set('category', ReportCategory::SPAM_INSTALL_REQUEST->value)
        ->set('details', 'Looks like spam')
        ->call('submitReport')
        ->assertHasNoErrors();

    expect(Report::query()->count())->toBe(1);
    $rep = Report::first();
    expect($rep->reporter_id)->toBe($seeker->id)
        ->and($rep->subject_type)->toBe('install_request')
        ->and($rep->subject_id)->toBe($installRequest->id)
        ->and($rep->status)->toBe(ReportStatus::OPEN);
});
