<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RoomList;
use App\Livewire\RoomBooking;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
], function() {
    // Auth routes (must be before 'auth' middleware group)
    Route::get(LaravelLocalization::transRoute('routes.login'), fn() => view('livewire.auth.login'))
        ->middleware('guest')
        ->name('login');
    
    Route::get(LaravelLocalization::transRoute('routes.register'), fn() => view('livewire.auth.register'))
        ->middleware('guest')
        ->name('register');
    
    // Room routes - require authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/', RoomList::class)->name('home');
        Route::get(LaravelLocalization::transRoute('routes.rooms'), RoomBooking::class)->name('rooms.show');
        Route::get(LaravelLocalization::transRoute('routes.my-reservations'), \App\Livewire\MyReservations::class)
            ->name('reservations.index');
    });

    require __DIR__.'/settings.php';
});
