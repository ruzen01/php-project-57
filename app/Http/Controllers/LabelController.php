<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::all();
        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        return view('labels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|unique:task_statuses',
        ], [
            'name.required' => 'Это обязательное поле', // Кастомное сообщение для пустого поля
            'name.min' => 'Имя статуса должно содержать хотя бы один символ.', // Кастомное сообщение для min
            'name.unique' => 'Статус с таким именем уже существует.', // Кастомное сообщение для уникальности
        ]);

        Label::create($request->only('name', 'description'));

        return redirect()->route('labels.index')->with('success', 'Метка успешно создана');
    }

    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $validated = $request->validate([
            'name' => 'required|min:1|unique:task_statuses',
        ], [
            'name.required' => 'Это обязательное поле', // Кастомное сообщение для пустого поля
        ]);


        $label->update($request->only('name', 'description'));

        return redirect()->route('labels.index')->with('success', 'Метка успешно обновлена');
    }

    public function destroy(Label $label)
    {
        if ($label->tasks()->count() > 0) {
            return redirect()->route('labels.index')->with('error', 'Не удалось удалить метку');
        }

        $label->delete();
        return redirect()->route('labels.index')->with('success', 'Метка успешно удалена');
    }
}