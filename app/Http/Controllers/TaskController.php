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
        // Валидация данных
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ]);

        // Добавляем ID авторизованного пользователя
        $validatedData['created_by_id'] = auth()->id();

        // Создание задачи
        $task = Task::create($validatedData);

        // Привязка меток к задаче, если они переданы
        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }
    
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
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

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->id !== $task->created_by_id) {
            return redirect()->route('tasks.index')->with('error', 'Only the creator can delete the task.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function show($id)
    {
        $task = Task::with('labels')->findOrFail($id); // Подгружаем связанные метки
        return view('tasks.show', compact('task'));
    }

    public function scopeLabel($query, $labelId)
    {
        return $query->whereHas('labels', function ($q) use ($labelId) {
            $q->where('id', $labelId);
        });
    }
}