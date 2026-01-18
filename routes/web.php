<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// LeesSpurt Reading Trainer routes
Route::get('/', fn () => Inertia::render('Welcome'))->name('home');
Route::get('/kid-settings', fn () => Inertia::render('KidSettings'))->name('kid.settings');
Route::get('/play/setup', fn () => Inertia::render('LeesSpurt/Setup'))->name('game.setup');
Route::get('/play/game', fn () => Inertia::render('LeesSpurt/Game'))->name('game.play');
Route::get('/play/results', fn () => Inertia::render('LeesSpurt/Results'))->name('game.results');

// Dashboard (for existing components)
Route::get('/dashboard', fn () => Inertia::render('Dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Keep settings routes for existing pages
require __DIR__.'/settings.php';
