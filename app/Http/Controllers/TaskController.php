<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Загружаем задачи вместе с привязанными метками
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::scope('label') // Фильтр по меткам
            ])
            ->with(['labels', 'status', 'creator', 'assignee']) // Заменяем 'executor' на 'assignee'
            ->get();

        $task_statuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.index', compact('tasks', 'task_statuses', 'users', 'labels'));
    }

    public function create()
    {
        $task_statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.create', compact('task_statuses', 'users', 'labels'));
    }

    public function store(Request $request)
    {
        // Валидация данных с пользовательскими сообщениями
        $validated = $request->validate([
            'name' => 'required|min:1|unique:tasks,name', // уникальность имени задачи
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя задачи должно содержать хотя бы один символ.',
            'name.unique' => 'Задача с таким именем уже существует.',
            'status_id.required' => 'Выберите статус задачи.',
            'status_id.exists' => 'Выбранный статус недействителен.',
            'assigned_to_id.exists' => 'Выбранный исполнитель недействителен.',
            'labels.*.exists' => 'Выбрана недействительная метка.',
        ]);

        // Добавляем ID авторизованного пользователя
        $validated['created_by_id'] = auth()->id();

        // Создание задачи
        $task = Task::create($validated);

        // Привязка меток к задаче, если они переданы
        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана');
    }

    public function edit(Task $task)
    {
        $task_statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.edit', compact('task', 'task_statuses', 'users', 'labels'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ]);

        $task->update($validatedData);

        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Задача успешно изменена.');
    }

    public function destroy(Task $task)
    {
        // Убедитесь, что пользователь аутентифицирован
        $authenticatedUser = auth()->user(); 

        if ($authenticatedUser) {
            $created_by_id = $authenticatedUser->id;
        } else {
            // В случае если пользователь не аутентифицирован, можно вернуть ошибку или редирект
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена');
    }

    public function show(int $id)
    {
        $task = Task::with('labels')->findOrFail($id); // Подгружаем связанные метки
        return view('tasks.show', compact('task'));
    }
}
