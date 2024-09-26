<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    public function index()
    {
        // Пагинация по 15 статусов
        $taskStatuses = TaskStatus::paginate(15);
        return view('task_statuses.index', compact('taskStatuses'));
    }

    public function create()
    {
        return view('task_statuses.create');
    }

    public function store(Request $request)
    {
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
        return view('task_statuses.edit', compact('taskStatus'));
    }

    public function update(Request $request, TaskStatus $taskStatus)
    {
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
        if ($taskStatus->tasks()->count() > 0) {
            return redirect()->route('task_statuses.index')
            ->with('error', 'Невозможно удалить статус, связанный с задачей');
        }

        $taskStatus->delete();

        return redirect()->route('task_statuses.index')->with('success', 'Статус успешно удалён');
    }
}
