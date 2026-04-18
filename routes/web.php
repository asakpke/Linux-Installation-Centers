<?php

use App\Enums\InstallRequestStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $recentInstallRequests = InstallRequest::query()
        ->where('status', '!=', InstallRequestStatus::SPAM)
        ->latest()
        ->limit(5)
        ->get(['id', 'title', 'city', 'country', 'status', 'created_at']);

    $recentSeekerSignups = User::query()
        ->where('role', UserRole::USER)
        ->where('is_active', true)
        ->latest()
        ->limit(5)
        ->get(['id', 'name', 'created_at']);

    $recentExpertSignups = User::query()
        ->where('role', UserRole::EXPERT)
        ->where('is_active', true)
        ->with(['expertProfile' => fn ($q) => $q->select('id', 'user_id', 'city', 'country')])
        ->latest()
        ->limit(5)
        ->get(['id', 'name', 'created_at']);

    $popularExperts = User::query()
        ->where('role', UserRole::EXPERT)
        ->where('is_active', true)
        ->with(['expertProfile' => fn ($q) => $q->select('id', 'user_id', 'city', 'country')])
        ->withCount('reviewsReceived')
        ->withAvg('reviewsReceived', 'rating')
        ->orderByDesc('reviews_received_count')
        ->orderByDesc('reviews_received_avg_rating')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get(['id', 'name', 'created_at']);

    return view('welcome', [
        'github_url' => 'https://github.com/asakpke/Linux-Installation-Centers',
        'recentInstallRequests' => $recentInstallRequests,
        'recentSeekerSignups' => $recentSeekerSignups,
        'recentExpertSignups' => $recentExpertSignups,
        'popularExperts' => $popularExperts,
    ]);
})->name('home');

Route::get('whats-new', function () {
    return view('changelog', [
        'title' => __("What's new").' · '.config('app.name'),
        'github_url' => 'https://github.com/asakpke/Linux-Installation-Centers',
    ]);
})->name('whats-new');

Route::get('support-the-project', function () {
    return view('support-the-project', [
        'title' => __('Support the project').' · '.config('app.name'),
        'github_url' => 'https://github.com/asakpke/Linux-Installation-Centers',
    ]);
})->name('support-the-project');

Route::livewire('profiles/{public_slug}', 'profile.public-show')->name('profiles.show');

Route::get('dashboard', function () {
    $user = auth()->user();
    if ($user->role === UserRole::ADMIN) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === UserRole::EXPERT) {
        return redirect()->route('expert.dashboard');
    }

    return view('dashboard');
})
    ->middleware(['auth', 'verified', 'active'])
    ->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'settings.profile')->name('settings.profile');
    Route::livewire('settings/public-profile', 'settings.public-profile')->name('settings.public-profile');
    Route::livewire('settings/password', 'settings.password')->name('settings.password');
    Route::livewire('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'active', 'role:user'])->group(function () {
    Route::livewire('requests', 'user.requests-index')->name('requests.index');
    Route::livewire('requests/create', 'user.request-form')->name('requests.create');
    Route::livewire('requests/{installRequest}/edit', 'user.request-form')->name('requests.edit');
    Route::livewire('requests/{installRequest}', 'user.request-show')->name('requests.show');
});

Route::middleware(['auth', 'verified', 'active', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', function () {
        return view('expert.dashboard');
    })->name('expert.dashboard');

    Route::livewire('expert/profile', 'expert.edit-profile')->name('expert.profile');
    Route::livewire('expert/assignments', 'expert.my-assignments')->name('expert.assignments');
    Route::livewire('expert/requests', 'expert.browse-requests')->name('expert.requests.index');
    Route::livewire('expert/requests/{installRequest}', 'expert.request-show')->name('expert.requests.show');
});

Route::middleware(['auth', 'verified', 'active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::livewire('users', 'admin.users-index')->name('users.index');
    Route::livewire('users/create', 'admin.user-form')->name('users.create');
    Route::livewire('users/{user}/edit', 'admin.user-form')->name('users.edit');

    Route::livewire('requests', 'admin.requests-index')->name('requests.index');
    Route::livewire('requests/{installRequest}', 'admin.request-show')->name('requests.show');

    Route::livewire('reports', 'admin.reports-index')->name('reports.index');
    Route::livewire('reports/{report}', 'admin.report-show')->name('reports.show');
});
