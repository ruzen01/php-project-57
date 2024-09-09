<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        return view('tasks.create', compact('statuses', 'users'));
    }

    public function store(Request $request)
    {
        \Log::info($request->all()); // Логирование данных запроса
        
        $request->validate([
            'name' => 'required',
            'status_id' => 'required',
            'created_by_id' => 'required',
        ]);

        $task = Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', __('Task created successfully.'));
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        return view('tasks.edit', compact('task', 'statuses', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required',
            'status_id' => 'required',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', __('Task updated successfully.'));
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