<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать задачу.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        return true; // Все пользователи могут просматривать задачи
    }

    /**
     * Проверяем, может ли пользователь создавать задачу.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user !== null; // Только аутентифицированные пользователи могут создавать задачи
    }

    /**
     * Проверяем, может ли пользователь редактировать задачу.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $user !== null; // Любой залогиненный пользователь может редактировать задачу
    }

    /**
     * Проверяем, может ли пользователь удалять задачу.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by_id; // Только автор задачи может её удалить
    }
}
