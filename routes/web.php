<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rotas do Kanban

Route::get('/kanban', [App\Http\Controllers\KanbanController::class, 'index'])->name('kanban.index');
Route::post('/kanban/update', [App\Http\Controllers\KanbanController::class, 'update'])->name('kanban.update');
Route::get('/kanban/{id}', [App\Http\Controllers\KanbanController::class, 'show'])->name('kanban.show');

//Change atribuiçao
//Route::put('/atualizar-status/{id}', [KanbanController::class, 'atualizarStatus']);

// Rotas de importação
Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');

