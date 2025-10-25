<?php

use App\Http\Controllers\AgendumController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs/open', [ProgramController::class, 'showByCode'])->name('programs.showByCode');

Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/programs/search', [ProgramController::class, 'search'])->name('programs.search');
Route::get('/programs/{program:code}', [ProgramController::class, 'show'])->name('programs.show');
Route::post('/programs/{program:code}/start', [ProgramController::class, 'start'])->name('programs.start');
Route::get('/programs/{program:code}/run/{agendum?}', [ProgramController::class, 'run'])->name('programs.run');
Route::post('/programs/{program:code}/end', [ProgramController::class, 'end'])->name('programs.end');



Route::post('/programs/{program:code}/agenda', [AgendumController::class, 'store'])->name('agenda.store');
Route::put('/programs/{program:code}/agenda/{agendum}', [AgendumController::class, 'update'])->name('agenda.update');
Route::delete('/programs/{program:code}/agenda/{agendum}', [AgendumController::class, 'destroy'])->name('agenda.destroy');
Route::post('/programs/{program:code}/agenda/{agendum}/start', [AgendumController::class, 'startAgendum'])->name('programs.agenda.start');
