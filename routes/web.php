<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('livewire/auth/login');
// })->name('home');

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('home');

require __DIR__.'/settings.php';
