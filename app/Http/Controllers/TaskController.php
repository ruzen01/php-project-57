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
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
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
        $this->authorize('create', Task::class);

        $taskStatuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.create', compact('taskStatuses', 'users', 'labels'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'name' => 'required|min:1|max:255|unique:tasks,name',
            'description' => 'nullable|string|max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ]);

        $validated['created_by_id'] = auth()->id();

        $task = Task::create($validated);

        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $taskStatuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
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
        $this->authorize('delete', $task);

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена');
    }

    public function show(int $id)
    {
        $task = Task::with('labels')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }
}
