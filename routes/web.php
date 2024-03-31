<?php

use App\Http\Controllers\GenerateLeagueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueTableController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/generate-league', [GenerateLeagueController::class, 'generateLeague'])->name('generate-league');
Route::get('/reset-league', [GenerateLeagueController::class, 'resetLeague']);
Route::post('/generate-next-week', [GenerateLeagueController::class, 'generateNextWeek']);
Route::get('/league-table', [LeagueTableController::class, 'index'])->name('league-table');
