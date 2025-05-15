<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/kanban ', [AuthController::class, 'showKanban'])->name('kanban');

use App\Http\Controllers\ImportController;

Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');
Route::put('/kanban/{id}', [KanbanController::class, 'update'])->name('kanban.update');
Route::get('/kanban/filter', [KanbanController::class, 'filter'])->name('kanban.filter');

//rotas de teste para Request do import

Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');

