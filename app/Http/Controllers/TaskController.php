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
        // Пагинация задач по 15 элементов
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('statusId'),
                AllowedFilter::exact('createdById'),
                AllowedFilter::exact('assignedToId'),
                AllowedFilter::scope('label'),
            ])
            ->with(['labels', 'status', 'creator', 'assignee'])
            ->paginate(15);

        $taskStatuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');

        return view('tasks.index', compact('tasks', 'taskStatuses', 'users', 'labels'));
    }

    public function create()
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.create', compact('taskStatuses', 'users', 'labels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|max:255|unique:tasks,name',
            'description' => 'nullable|string|max:1000',
            'statusId' => 'required|exists:task_statuses,id',
            'assignedToId' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя задачи должно содержать хотя бы один символ.',
            'name.max' => 'Имя задачи не должно превышать 255 символов.',
            'name.unique' => 'Задача с таким именем уже существует.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
            'statusId.required' => 'Выберите статус задачи.',
            'statusId.exists' => 'Выбранный статус недействителен.',
            'assignedToId.exists' => 'Выбранный исполнитель недействителен.',
            'labels.*.exists' => 'Выбрана недействительная метка.',
        ]);

        $validated['createdById'] = auth()->id();

        $task = Task::create($validated);

        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана');
    }

    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'statusId' => 'required|exists:task_statuses,id',
            'assignedToId' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ], [
            'name.required' => 'Это обязательное поле',
            'name.max' => 'Имя задачи не должно превышать 255 символов.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
        ]);

        $task->update($validatedData);

        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Задача успешно изменена.');
    }

    public function destroy(Task $task)
    {
        $authenticatedUser = auth()->user();

        if ($authenticatedUser !== null) {
            $createdById = $authenticatedUser->id;
        } else {
            return redirect()->route('login')->with('error', 'You must be logged in to perform this action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена');
    }

    public function show(int $id)
    {
        $task = Task::with('labels')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }
}
