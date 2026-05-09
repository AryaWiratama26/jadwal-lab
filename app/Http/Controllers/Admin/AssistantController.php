<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index()
    {
        $assistants = Assistant::withCount('schedules')->paginate(15);
        return view('admin.assistants.index', compact('assistants'));
    }

    public function create()
    {
        return view('admin.assistants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        Assistant::create($validated);

        return redirect()->route('admin.assistants.index')
            ->with('success', 'Asisten berhasil ditambahkan!');
    }

    public function edit(Assistant $assistant)
    {
        return view('admin.assistants.edit', compact('assistant'));
    }

    public function update(Request $request, Assistant $assistant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        $assistant->update($validated);

        return redirect()->route('admin.assistants.index')
            ->with('success', 'Asisten berhasil diperbarui!');
    }

    public function destroy(Assistant $assistant)
    {
        $assistant->delete();

        return redirect()->route('admin.assistants.index')
            ->with('success', 'Asisten berhasil dihapus!');
    }
}
