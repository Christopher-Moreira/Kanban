<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;


Route::get('/kanban ', [AuthController::class, 'showKanban'])->name('kanban');

//rotas de teste para Request do import

Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');