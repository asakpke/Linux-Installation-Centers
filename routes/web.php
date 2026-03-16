<?php

use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'github_url' => 'https://github.com/asakpke/Linux-Installation-Centers',
    ]);
})->name('home');

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
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', 'settings.profile')->name('settings.profile');
    Route::livewire('settings/password', 'settings.password')->name('settings.password');
    Route::livewire('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'role:expert'])->group(function () {
    Route::get('/expert/dashboard', function () {
        return view('expert.dashboard');
    })->name('expert.dashboard');

    Route::livewire('expert/profile', 'expert.edit-profile')->name('expert.profile');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::livewire('users', 'admin.users-index')->name('users.index');
    Route::livewire('users/create', 'admin.user-form')->name('users.create');
    Route::livewire('users/{user}/edit', 'admin.user-form')->name('users.edit');
});
