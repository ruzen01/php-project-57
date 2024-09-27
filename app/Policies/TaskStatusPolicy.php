<?php

namespace App\Policies;

use App\Models\TaskStatus;
use App\Models\User;

class TaskStatusPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать статус задачи.
     */
    public function view(User $user, TaskStatus $taskStatus): bool
    {
        return true; // Все пользователи могут просматривать статусы
    }

    /**
     * Проверяем, может ли пользователь создавать статусы задач.
     */
    public function create(User $user): bool
    {
        return true; // Любой аутентифицированный пользователь может создавать статусы
    }

    /**
     * Проверяем, может ли пользователь обновлять статусы задач.
     */
    public function update(User $user, TaskStatus $taskStatus): bool
    {
        return true; // Любой аутентифицированный пользователь может редактировать статусы
    }

    /**
     * Проверяем, может ли пользователь удалять статус задачи.
     */
    public function delete(User $user, TaskStatus $taskStatus): bool
    {
        return true; // Любой аутентифицированный пользователь может удалять статусы
    }
}
