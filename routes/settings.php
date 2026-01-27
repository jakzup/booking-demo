<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::middleware(['auth'])->group(function () {
    Route::get(LaravelLocalization::transRoute('routes.settings.profile'), Profile::class)->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get(LaravelLocalization::transRoute('routes.settings.password'), Password::class)->name('user-password.edit');
    
    Route::get(LaravelLocalization::transRoute('routes.settings.appearance'), Appearance::class)->name('appearance.edit');

    Route::get(LaravelLocalization::transRoute('routes.settings.two-factor'), TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


