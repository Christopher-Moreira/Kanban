<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KanbanController;


// Rotas do Kanban
Route::get('/kanban', [KanbanController::class, 'index']);

//Change atribuiçao
//Route::put('/atualizar-status/{id}', [KanbanController::class, 'atualizarStatus']);

// Rotas de importação
Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');