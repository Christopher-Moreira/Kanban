<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::get('/kanban ', [AuthController::class, 'showKanban'])->name('kanban');
