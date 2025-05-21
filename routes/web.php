<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ReminderController;

// Rotas do Kanban

Route::get('/kanban', [App\Http\Controllers\KanbanController::class, 'index'])->name('kanban.index');
Route::post('/kanban/update', [App\Http\Controllers\KanbanController::class, 'update'])->name('kanban.update');
Route::get('/kanban/{id}', [App\Http\Controllers\KanbanController::class, 'show'])->name('kanban.show');

// Rotas de importação
Route::get('/import', [ImportController::class, 'showImportForm'])->name('import');
Route::post('/import', [ImportController::class, 'import'])->name('import');

//visão para demonstração
//Route::get('/visual', [App\Http\Controllers\KanbanController::class, 'indexTwo'])->name('kanban.idea');

    //Rotas Reminder

Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');