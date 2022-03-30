<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('index');
})->name('index');

Auth::routes();

Route::resources(['task_statuses' => TaskStatusController::class]);
Route::resources(['tasks' => TaskController::class]);
