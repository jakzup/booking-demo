<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/' . default_locale());

// Catch unsupported locales and redirect to default locale with the same path
Route::get('/{locale}/{path?}', function ($locale, $path = '') {
    return redirect('/' . default_locale() . '/' . $path);
})->where('locale', '^(?!' . locale_pattern() . ').*$')->where('path', '.*');

Route::prefix('{locale}')->where(['locale' => locale_pattern()])->group(function () {
    Route::view('/', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('home');

    require __DIR__.'/settings.php';
});
