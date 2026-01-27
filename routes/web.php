<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RoomList;
use App\Livewire\RoomBooking;

Route::redirect('/', '/' . default_locale());

// Redirect non-localized auth routes to localized versions
Route::get('/login', fn() => redirect('/' . default_locale() . '/login'));
Route::get('/register', fn() => redirect('/' . default_locale() . '/register'));

// Catch unsupported locales and redirect to default locale with the same path
Route::get('/{locale}/{path?}', function ($locale, $path = '') {
    return redirect('/' . default_locale() . '/' . $path);
})->where('locale', '^(?!' . locale_pattern() . ').*$')->where('path', '.*');

Route::prefix('{locale}')->where(['locale' => locale_pattern()])->group(function () {
    // Auth routes (must be before 'auth' middleware group)
    Route::get('/login', fn() => view('livewire.auth.login'))->middleware('guest')->name('login');
    Route::get('/register', fn() => view('livewire.auth.register'))->middleware('guest')->name('register');
    
    // Room routes - require authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/', RoomList::class)->name('home');
        Route::get('/rooms/{room}', RoomBooking::class)->name('rooms.show');
        Route::get('/my-reservations', \App\Livewire\MyReservations::class)->name('reservations.index');
        Route::get('/moje-rezervacije', \App\Livewire\MyReservations::class)->name('reservations.index.sl');
    });

    require __DIR__.'/settings.php';
});
