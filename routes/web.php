<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/switch-locale/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('switch-locale');
