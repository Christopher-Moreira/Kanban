<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/kanban ', [AuthController::class, 'showKanban'])->name('kanban');

use App\Http\Controllers\ImportController;
use App\Http\Controllers\KanbanController;

Route::get('/', function () {
    return redirect()->route('kanban.index');
});

// Rotas para o Kanban
Route::prefix('kanban')->group(function () {
    Route::get('/', [KanbanController::class, 'index'])->name('kanban.index');
    Route::post('/', [KanbanController::class, 'store'])->name('kanban.store');
    Route::post('/update-status', [KanbanController::class, 'updateStatus'])->name('kanban.updateStatus');
    Route::get('/statistics', [KanbanController::class, 'getStatistics'])->name('kanban.statistics');
    Route::get('/{id}', [KanbanController::class, 'show'])->name('kanban.show');
    Route::delete('/{id}', [KanbanController::class, 'destroy'])->name('kanban.destroy');
});

//rotas de teste para Request do import

Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');

