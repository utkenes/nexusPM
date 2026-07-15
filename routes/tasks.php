<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::patch('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('tasks/{task}/watch', [TaskController::class, 'toggleWatch'])->name('tasks.toggleWatch');
    Route::post('tasks/{task}/labels', [TaskController::class, 'assignLabels'])->name('tasks.assignLabels');
    Route::post('tasks/bulk', [TaskController::class, 'bulkUpdate'])->name('tasks.bulkUpdate');
});
