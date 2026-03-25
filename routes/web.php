<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BusinessController;
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

Route::resource('businesses', BusinessController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
