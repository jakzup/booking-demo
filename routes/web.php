<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RoomList;
use App\Livewire\RoomBooking;

Route::redirect('/', '/' . default_locale());

// Catch unsupported locales and redirect to default locale with the same path
Route::get('/{locale}/{path?}', function ($locale, $path = '') {
    return redirect('/' . default_locale() . '/' . $path);
})->where('locale', '^(?!' . locale_pattern() . ').*$')->where('path', '.*');

Route::prefix('{locale}')->where(['locale' => locale_pattern()])->group(function () {
    Route::view('/', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('home');

    // Room routes
    Route::get('/rooms', RoomList::class)->name('rooms.index');
    Route::get('/rooms/{room}', RoomBooking::class)->name('rooms.show');

    // Reservation routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/my-reservations', function () {
            // Create a component for this
        })->name('reservations.index');
    });

    require __DIR__.'/settings.php';
});
