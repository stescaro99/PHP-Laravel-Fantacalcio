<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerImportController;
use App\Http\Controllers\StatsImportController;

Route::get('/', function () {
    return view('welcome');
});

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
