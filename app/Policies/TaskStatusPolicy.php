<?php

namespace App\Policies;

use App\Models\TaskStatus;
use App\Models\User;

class TaskStatusPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать статус задачи.
     *
     * @param  User  $user
     * @param  TaskStatus  $taskStatus
     * @return bool
     */
    public function view(User $user, TaskStatus $taskStatus): bool
    {
        return true; // Все пользователи могут просматривать статусы
    }

    /**
     * Проверяем, может ли пользователь создавать статусы задач.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user !== null; // Только аутентифицированные пользователи могут создавать статусы
    }

    /**
     * Проверяем, может ли пользователь обновлять статусы задач.
     *
     * @param  User  $user
     * @param  TaskStatus  $taskStatus
     * @return bool
     */
    public function update(User $user, TaskStatus $taskStatus): bool
    {
        return $user !== null; // Любой залогиненный пользователь может редактировать статусы задач
    }

    /**
     * Проверяем, может ли пользователь удалять статус задачи.
     *
     * @param  User  $user
     * @param  TaskStatus  $taskStatus
     * @return bool
     */
    public function delete(User $user, TaskStatus $taskStatus): bool
    {
        return $user !== null; // Любой залогиненный пользователь может удалять статусы задач
    }
}
