<?php

use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// API routes
Route::get('/api/words/{lang}/{length}', [WordController::class, 'index']);

// LeesSpurt Reading Trainer routes
Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

// Language-prefixed routes
foreach (['en', 'nl'] as $lang) {
    Route::prefix($lang)->group(function () use ($lang) {
        Route::get('/settings', fn () => Inertia::render('KidSettings', ['lang' => $lang]))->name("$lang.kid.settings");
        Route::get('/play/setup', fn () => Inertia::render('LeesSpurt/Setup', ['lang' => $lang]))->name("$lang.game.setup");
        Route::get('/play/game', fn () => Inertia::render('LeesSpurt/Game', ['lang' => $lang]))->name("$lang.game.play");
        Route::get('/play/results', fn () => Inertia::render('LeesSpurt/Results', ['lang' => $lang]))->name("$lang.game.results");
    });
}

// Dashboard (for existing components)
Route::get('/dashboard', fn () => Inertia::render('Dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Keep settings routes for existing pages
require __DIR__.'/settings.php';
