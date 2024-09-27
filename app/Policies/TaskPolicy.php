<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать задачу.
     */
    public function view(User $user, Task $task): bool
    {
        return true; // Все пользователи могут просматривать задачи
    }

    /**
     * Проверяем, может ли пользователь создавать задачу.
     */
    public function create(User $user): bool
    {
        return true; // Любой аутентифицированный пользователь может создавать задачи
    }

    /**
     * Проверяем, может ли пользователь редактировать задачу.
     */
    public function update(User $user, Task $task): bool
    {
        return true; // Любой аутентифицированный пользователь может редактировать задачи
    }

    /**
     * Проверяем, может ли пользователь удалять задачу.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by_id; // Только автор задачи может её удалить
    }
}
