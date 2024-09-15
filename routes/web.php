<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LabelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Профиль пользователя только для авторизованных пользователей
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Публичные маршруты для просмотра задач, статусов и меток
Route::get('task_statuses', [TaskStatusController::class, 'index'])->name('task_statuses.index'); // Публичный просмотр статусов
Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index'); // Публичный просмотр задач

Route::get('labels', [LabelController::class, 'index'])->name('labels.index'); // Публичный просмотр меток

// Только для авторизованных пользователей (создание, редактирование, удаление)
Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('labels', LabelController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('task_statuses', TaskStatusController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
});

 Route::get('tasks/{id}', [TaskController::class, 'show'])->name('tasks.show'); // Публичный просмотр конкретной задачи
