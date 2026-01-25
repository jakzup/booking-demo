<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    // Locale-aware redirects - redirect to appropriate profile based on current locale
    Route::get('settings', function () {
        return redirect(route_localized('profile.edit'));
    });
    
    Route::get('nastavitve', function () {
        return redirect(route_localized('profile.edit'));
    });

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
    Route::livewire('nastavitve/profil', Profile::class)->name('profile.edit.sl');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/password', Password::class)->name('user-password.edit');
    Route::livewire('nastavitve/geslo', Password::class)->name('user-password.edit.sl');
    
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::livewire('nastavitve/izgled', Appearance::class)->name('appearance.edit.sl');

    Route::livewire('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    
    Route::livewire('nastavitve/dvostopenjska-overitev', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show.sl');
});


