<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
        ->middleware('role:admin|manager')
        ->name('tasks.overdue');

    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])
        ->name('tasks.comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::get('/team-tasks', [TaskController::class, 'teamTasks'])
    ->middleware('role:admin|manager')
    ->name('tasks.team');

    Route::resource('tasks', TaskController::class);
});

Route::middleware(['auth', 'role:admin|manager'])->group(function () {
    Route::post('/projects/{project}/documents', [DocumentController::class, 'store'])
        ->name('projects.documents.store');
    Route::post('/tasks/{task}/documents', [DocumentController::class, 'storeForTask'])
        ->name('tasks.documents.store');

    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy');

    Route::resource('clients', ClientController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
});

Route::middleware(['auth', 'role:admin|manager'])->group(function () {

    Route::resource('clients', ClientController::class)
        ->except(['destroy']);

    Route::resource('projects', ProjectController::class)
        ->except(['destroy']);

});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
        ->name('clients.destroy');

    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    Route::resource('users', UserController::class);

});

require __DIR__.'/auth.php';