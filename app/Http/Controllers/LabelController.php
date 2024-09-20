<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'name' => 'required|min:1|max:255|unique:labels,name', // Добавлено ограничение max:255
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя метки должно содержать хотя бы один символ.',
            'name.max' => 'Имя метки не должно превышать 255 символов.', // Новое сообщение об ошибке
            'name.unique' => 'Метка с таким именем уже существует.',
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
            'name' => [
                'required',
                'min:1',
                'max:255', // Добавлено ограничение max:255
                Rule::unique('labels', 'name')->ignore($label->id),
            ],
        ], [
            'name.required' => 'Это обязательное поле',
            'name.min' => 'Имя метки должно содержать хотя бы один символ.',
            'name.max' => 'Имя метки не должно превышать 255 символов.', // Новое сообщение об ошибке
            'name.unique' => 'Метка с таким именем уже существует.',
        ]);

        $label->update($request->only('name', 'description'));

        return redirect()->route('labels.index')->with('success', 'Метка успешно изменена');
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
