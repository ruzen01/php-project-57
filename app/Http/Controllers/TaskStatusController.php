<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    // Отображение списка всех статусов
    public function index()
    {
        $taskStatuses = TaskStatus::all();
        return view('task_statuses.index', compact('taskStatuses'));
    }

    // Показ формы для создания нового статуса
    public function create()
    {
        return view('task_statuses.create');
    }

    // Сохранение нового статуса в базе данных
    public function store(Request $request)
    {
        // Полная валидация с ограничением на длину имени
        $validated = $request->validate([
            'name' => 'required|min:1|max:255|unique:task_statuses', // Добавлено ограничение max:255
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.',
            'name.max' => 'Имя статуса не должно превышать 255 символов.', // Новое сообщение об ошибке
            'name.unique' => 'Статус с таким именем уже существует.',
        ]);

        TaskStatus::create($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно создан');
    }

    // Показ формы для редактирования существующего статуса
    public function edit(TaskStatus $task_status)
    {
        return view('task_statuses.edit', compact('task_status'));
    }

    // Обновление существующего статуса в базе данных
    public function update(Request $request, TaskStatus $task_status)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:255|unique:task_statuses,name,' . $task_status->id, // Добавлено ограничение max:255
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.',
            'name.max' => 'Имя статуса не должно превышать 255 символов.', // Новое сообщение об ошибке
            'name.unique' => 'Статус с таким именем уже существует.',
        ]);

        $task_status->update($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно изменён');
    }

    // Удаление статуса
    public function destroy(TaskStatus $task_status)
    {
        if ($task_status->tasks()->count() > 0) {
            return redirect()
                ->route('task_statuses.index')
                ->with('error', 'Невозможно удалить статус, связанный с задачей');
        }

        $task_status->delete();

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно удалён');
    }
}
