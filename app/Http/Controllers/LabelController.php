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
        $this->authorize('create', Label::class);
        return view('labels.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Label::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:labels',
        ]);

        Label::create($validated);

        return redirect()->route('labels.index')->with('success', 'Метка успешно создана');
    }

    public function edit(Label $label)
    {
        $this->authorize('update', $label);
        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $this->authorize('update', $label);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:labels,name,' . $label->id,
        ]);

        $label->update($validated);

        return redirect()->route('labels.index')->with('success', 'Метка успешно обновлена');
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);

        $label->delete();
        return redirect()->route('labels.index')->with('success', 'Метка успешно удалена');
    }
}
