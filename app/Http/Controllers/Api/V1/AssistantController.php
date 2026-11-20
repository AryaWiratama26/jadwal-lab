<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssistantResource;
use App\Models\Assistant;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $assistants = Assistant::withCount('schedules')
            ->when(! empty($validated['search']), function ($q) use ($validated) {
                $s = '%' . $validated['search'] . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $s)->orWhere('nim', 'like', $s));
            })
            ->orderBy('name')
            ->paginate($validated['per_page'] ?? 15)
            ->withQueryString();

        return AssistantResource::collection($assistants);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        $assistant = Assistant::create($validated);

        return (new AssistantResource($assistant))
            ->additional(['message' => 'Asisten berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Assistant $assistant)
    {
        $assistant->loadCount('schedules');

        return new AssistantResource($assistant);
    }

    public function update(Request $request, Assistant $assistant)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        $assistant->update($validated);

        return (new AssistantResource($assistant))
            ->additional(['message' => 'Asisten berhasil diperbarui.']);
    }

    public function destroy(Assistant $assistant)
    {
        $assistant->delete();

        return response()->json(['message' => 'Asisten berhasil dihapus.']);
    }
}
