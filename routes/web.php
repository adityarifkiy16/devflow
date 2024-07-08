<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard'); // Redirect to the dashboard or any other page
    } else {
        return redirect('/login');
    }
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
    Route::post('/logout', LogoutController::class)->name('logout');
    Route::resource('project', ProjectController::class);
    Route::get('/projects/{project}/kanban', [ProjectController::class, 'kanban'])->name('project.kanban');

    Route::prefix('task')->controller(TaskController::class)->group(function () {
        Route::post('/store', 'store');
        Route::post('/update-task-status', 'updateTaskStatus');
        Route::post('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'destroy')->name('task.destroy');
        Route::get('/edit/{id}', 'edit')->name('task.edit');
    });

    //full access 
    Route::middleware(['role:1,2,3'])->group(function () {
        Route::resource('users', UserController::class);
    });

    //edit
    Route::middleware(['role:1,2,3,4'])->group(function () {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    //view
    Route::middleware(['role:1,2'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });
});
