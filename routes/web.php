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

// Rotas para Lembretes
Route::prefix('reminders')->name('reminders.')->group(function () {
    Route::post('/', [ReminderController::class, 'store'])->name('store');
    Route::patch('/{reminder}', [ReminderController::class, 'update'])->name('update');
    Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('destroy');
    Route::patch('/{reminder}/complete', [ReminderController::class, 'complete'])->name('complete');
    Route::patch('/{reminder}/snooze', [ReminderController::class, 'snooze'])->name('snooze');
    
    // Rotas AJAX
    Route::get('/check-updates/{contractId}', [ReminderController::class, 'checkUpdates'])->name('check-updates');
    Route::get('/upcoming', [ReminderController::class, 'getUpcomingReminders'])->name('upcoming');
    Route::get('/overdue', [ReminderController::class, 'getOverdueReminders'])->name('overdue');
    Route::post('/bulk-complete', [ReminderController::class, 'bulkComplete'])->name('bulk-complete');
});