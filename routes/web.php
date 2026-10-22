<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PostController;

Route::resource('books', BookController::class);

Route::resource('products', ProductController::class);

Route::resource('students', StudentController::class);

Route::resource('classrooms', ClassroomController::class)
    ->only(['index', 'store', 'destroy']);

    
Route::middleware('auth')->group(function () {

    Route::resource('tasks', TaskController::class);

    Route::patch('/tasks/{task}/toggle',
        [TaskController::class, 'toggle'])
        ->name('tasks.toggle');

    Route::resource('posts', PostController::class);

    Route::get('/posts-trash', [PostController::class, 'trash'])
        ->name('posts.trash');

    Route::patch('/posts-trash/{id}/restore',
        [PostController::class, 'restore'])
        ->name('posts.restore');

    Route::delete('/posts-trash/{id}/force-delete',
        [PostController::class, 'forceDelete'])
        ->name('posts.forceDelete');
});
