<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Livewire;

Route::middleware(['auth'])->group(function () {
    // Locale-aware redirects - redirect to appropriate profile based on current locale
    Route::get('settings', function () {
        return redirect(route_localized('profile.edit'));
    });
    
    Route::get('nastavitve', function () {
        return redirect(route_localized('profile.edit'));
    });

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('nastavitve/profil', Profile::class)->name('profile.edit.sl');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('nastavitve/geslo', Password::class)->name('user-password.edit.sl');
    
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('nastavitve/izgled', Appearance::class)->name('appearance.edit.sl');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    
    Route::get('nastavitve/dvostopenjska-overitev', TwoFactor::class)
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


