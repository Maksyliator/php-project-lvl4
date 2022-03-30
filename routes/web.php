<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskStatusController;

Route::get('/', function () {
    return view('index');
})->name('index');

Auth::routes();

Route::resources(['task_statuses' => TaskStatusController::class]);
