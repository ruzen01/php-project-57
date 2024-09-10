<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        // Загружаем задачи вместе с привязанными метками
        $tasks = Task::with('labels')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $task_statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all(); // Получаем все метки
        return view('tasks.create', compact('task_statuses', 'users', 'labels')); // Передаем метки в представление
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'created_by_id' => 'required|exists:users,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'array',  // Массив ID меток
            'labels.*' => 'exists:labels,id',  // Проверяем существование каждой метки
        ]);

        $task = Task::create($validatedData);

        // Привязка меток
        if ($request->has('labels')) {
            $task->labels()->sync($request->labels);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }


    public function edit(Task $task)
    {
        $task_statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all(); // Получаем все метки
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
            return redirect()->route('tasks.index')->with('error', __('Only the creator can delete the task.'));
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', __('Task deleted successfully.'));
    }
}