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
            'name' => 'required|min:1|max:255|unique:task_statuses',
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.',
            'name.max' => 'Имя статуса не должно превышать 255 символов.',
            'name.unique' => 'Статус с таким именем уже существует.',
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
            'name' => 'required|min:1|max:255|unique:task_statuses,name,' . $taskStatus->id,
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.',
            'name.max' => 'Имя статуса не должно превышать 255 символов.',
            'name.unique' => 'Статус с таким именем уже существует.',
        ]);

        $taskStatus->update($validated);

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно изменён');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $this->authorize('delete', $taskStatus);

        if ($taskStatus->tasks()->count() > 0) {
            return redirect()->route('task_statuses.index')
            ->with('error', 'Невозможно удалить статус, связанный с задачей');
        }

        $taskStatus->delete();
        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно удалён');
    }
}
