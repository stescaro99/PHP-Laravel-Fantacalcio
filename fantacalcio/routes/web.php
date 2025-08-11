<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerImportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/players', [PlayerImportController::class, 'index'])->name('players.index');
Route::get('/players/import', [PlayerImportController::class, 'showImportForm'])->name('players.import.form');
Route::post('/players/import', [PlayerImportController::class, 'import'])->name('players.import');
Route::post('/players/import-background', [PlayerImportController::class, 'importInBackground'])->name('players.import.background');
Route::get('/players/template', [PlayerImportController::class, 'exportTemplate'])->name('players.export.template');
