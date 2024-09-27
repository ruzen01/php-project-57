<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать метку.
     */
    public function view(User $user, Label $label): bool
    {
        return true; // Все пользователи могут просматривать метки
    }

    /**
     * Проверяем, может ли пользователь создавать метку.
     */
    public function create(User $user): bool
    {
        return true; // Любой аутентифицированный пользователь может создавать метки
    }

    /**
     * Проверяем, может ли пользователь обновлять метку.
     */
    public function update(User $user, Label $label): bool
    {
        return true; // Любой аутентифицированный пользователь может редактировать метки
    }

    /**
     * Проверяем, может ли пользователь удалять метку.
     */
    public function delete(User $user, Label $label): bool
    {
        return true; // Любой аутентифицированный пользователь может удалять метки
    }
}
