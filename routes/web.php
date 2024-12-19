<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

Route::get('/home', function () {
    return redirect()->route('contacts.index');
})->name('home');

Route::resource('contacts', ContactController::class);
