<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    // public function __construct()
    // {
    //     // Middleware auth применено ко всем методам
    //     $this->middleware('auth');
    // }

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
        // Полная валидация с кастомными сообщениями
        $validated = $request->validate([
            'name' => 'required|min:1|unique:task_statuses',
        ], [
            'name.required' => 'Это обязательное поле', // Кастомное сообщение для пустого поля
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.', // Кастомное сообщение для min
            'name.unique' => 'Статус с таким именем уже существует.', // Кастомное сообщение для уникальности
        ]);

        // Создание нового статуса
        TaskStatus::create($validated);

        // Сообщение об успешном создании статуса
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
            'name' => 'required|min:1|unique:task_statuses,name,' . $task_status->id,
        ]);

        $task_status->update($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно изменён');
    }

    // Удаление статуса
    public function destroy(TaskStatus $task_status)
    {
        if ($task_status->tasks()->count() > 0) {
            return redirect()->route('task_statuses.index')->with('error', 'Невозможно удалить статус, связанный с задачей');
        }

        $task_status->delete();

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно удалён');
    }
}
