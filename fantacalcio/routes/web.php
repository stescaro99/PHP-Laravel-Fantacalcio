<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerImportController;
use App\Http\Controllers\StatsImportController;
use App\Http\Controllers\PlayerPreferenceController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/players', [PlayerImportController::class, 'index'])->name('players.index');
Route::get('/players/import', [PlayerImportController::class, 'showImportForm'])->name('players.import.form');
Route::post('/players/import', [PlayerImportController::class, 'import'])->name('players.import');
Route::post('/players/import-background', [PlayerImportController::class, 'importInBackground'])->name('players.import.background');
Route::get('/players/template', [PlayerImportController::class, 'exportTemplate'])->name('players.export.template');
Route::get('/players/{id}', [PlayerImportController::class, 'show'])->name('players.show');

Route::get('/stats', [StatsImportController::class, 'index'])->name('stats.index');
Route::get('/stats/import', [StatsImportController::class, 'showImportForm'])->name('stats.import.form');
Route::post('/stats/import', [StatsImportController::class, 'import'])->name('stats.import');
Route::post('/stats/import-background', [StatsImportController::class, 'importInBackground'])->name('stats.import.background');
Route::get('/stats/template', [StatsImportController::class, 'exportTemplate'])->name('stats.export.template');

// Player Preferences API (temporanea, semplice JSON)
Route::middleware('auth')->group(function () {
    Route::get('/player-preferences', [PlayerPreferenceController::class, 'index'])->name('player_prefs.index');
    Route::post('/player-preferences/upsert', [PlayerPreferenceController::class, 'upsert'])->name('player_prefs.upsert');
    Route::delete('/player-preferences/remove', [PlayerPreferenceController::class, 'remove'])->name('player_prefs.remove');
    Route::get('/player-preferences/get', [PlayerPreferenceController::class, 'get'])->name('player_prefs.get');
});
