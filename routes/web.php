<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');

    Route::get('/tasks/overdue', [TaskController::class, 'overdue'])
        ->middleware('role:admin')
        ->name('tasks.overdue');

    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])
        ->name('tasks.comments.store');

    Route::resource('tasks', TaskController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/projects/{project}/documents', [DocumentController::class, 'store'])
        ->name('projects.documents.store');

    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
});

require __DIR__.'/auth.php';