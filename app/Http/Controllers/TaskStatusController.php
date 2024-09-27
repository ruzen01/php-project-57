<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function index()
    {
        $taskStatuses = TaskStatus::paginate(15);
        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create()
    {
        $this->authorize('create', TaskStatus::class);
        return view('task_statuses.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', TaskStatus::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses',
        ]);

        TaskStatus::create($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно создан');
    }

    public function edit(TaskStatus $taskStatus)
    {
        $this->authorize('update', $taskStatus);
        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
        $this->authorize('update', $taskStatus);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses,name,' . $taskStatus->id,
        ]);

        $taskStatus->update($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно обновлён');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $this->authorize('delete', $taskStatus);

        $taskStatus->delete();
        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно удалён');
    }
}
