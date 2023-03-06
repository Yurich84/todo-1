<?php

use App\Modules\Todo\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('todos/done/{todo}', [TodoController::class, 'done'])->name('done');

Route::apiResource('todos', 'TodoController');
