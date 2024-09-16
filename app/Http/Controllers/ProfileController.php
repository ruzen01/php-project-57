<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Получаем пользователя и сохраняем в переменную
        $user = $request->user();

        // Проверяем, что пользователь аутентифицирован
        if ($user === null) {
            return Redirect::route('login')->with('error', 'Пожалуйста, войдите в систему для обновления профиля.');
        }

        // Заполняем данные пользователя
        $user->fill($request->validated());

        // Проверяем, изменилось ли поле email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Сохраняем пользователя
        $user->save();

        // Перенаправляем с сообщением об успешном обновлении
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
