<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    /**
     * Проверяем, может ли пользователь просматривать метку.
     *
     * @param  User  $user
     * @param  Label  $label
     * @return bool
     */
    public function view(User $user, Label $label): bool
    {
        return true; // Все пользователи могут просматривать метки
    }

    /**
     * Проверяем, может ли пользователь создавать метку.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user !== null; // Только аутентифицированные пользователи могут создавать метки
    }

    /**
     * Проверяем, может ли пользователь обновлять метку.
     *
     * @param  User  $user
     * @param  Label  $label
     * @return bool
     */
    public function update(User $user, Label $label): bool
    {
        return $user !== null; // Любой залогиненный пользователь может редактировать метки
    }

    /**
     * Проверяем, может ли пользователь удалять метку.
     *
     * @param  User  $user
     * @param  Label  $label
     * @return bool
     */
    public function delete(User $user, Label $label): bool
    {
        return $user !== null; // Любой залогиненный пользователь может удалять метки
    }
}
