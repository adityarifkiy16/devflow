<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::resource('users', UserController::class);
    Route::resource('project', ProjectController::class);
    Route::get('/projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('project.kanban');

    Route::prefix('task')->group(function () {
        Route::post('/store', [TaskController::class, 'store']);
        Route::post('/update-task-status', [TaskController::class, 'updateTaskStatus']);
        Route::post('/update/{id}', [TaskController::class, 'update']);
    });
});
