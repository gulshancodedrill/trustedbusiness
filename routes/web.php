<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/business/techfix-pro', function () {
    return view('business.techfix-pro');
});

Route::middleware('logged.in')->group(function () {
    // Restrict create/edit/store/update/destroy, but keep listing & viewing public.
    Route::resource('businesses', BusinessController::class)->except([
        'index',
        'show',
    ]);

    // Used by the business create/edit dropdowns.
    Route::get('/locations/states', [LocationController::class, 'states'])->name('locations.states');
    Route::get('/locations/cities', [LocationController::class, 'cities'])->name('locations.cities');
});

// Keep listing & viewing public.
Route::resource('businesses', BusinessController::class)->only([
    'index',
    'show',
]);

Route::middleware('logged.in')->group(function () {
    // React-inspired entrypoint: open popup with two choices.
    Route::get('/add-business', function () {
        return view('business.add-business');
    })->name('businesses.add');

    // "Someone else's business" flow (suggestion).
    Route::get('/suggest/businesses', [BusinessController::class, 'createSuggest'])->name('businesses.suggest');
    Route::post('/suggest/businesses', [BusinessController::class, 'storeSuggest'])->name('businesses.suggest.store');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
