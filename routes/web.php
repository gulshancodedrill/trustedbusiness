<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewVoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Static design page for UI checks
Route::get('/business/techfix-pro', function () {
    return view('business.techfix-pro');
})->name('business.techfix-pro');

// Business detail page as single-page route: /business/{id}
Route::get('/business/{business}', [BusinessController::class, 'show'])
    ->whereNumber('business')
    ->name('business.detail');

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

54

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/business/{business}/reviews', [ReviewController::class, 'store'])
        ->name('business.reviews.store');

    Route::post('/reviews/{review}/vote', [ReviewVoteController::class, 'vote'])
        ->name('reviews.vote');
});

require __DIR__.'/auth.php';
