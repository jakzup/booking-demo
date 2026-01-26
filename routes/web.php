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
    // Room routes - require authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/', RoomList::class)->name('home');
        Route::get('/rooms/{room}', RoomBooking::class)->name('rooms.show');
        Route::get('/moje-rezervacije', \App\Livewire\MyReservations::class)->name('reservations.index');
    });

    require __DIR__.'/settings.php';
});
