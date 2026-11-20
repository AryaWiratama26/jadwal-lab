<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'nullable|integer|exists:courses,id',
            'hari' => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'program' => 'nullable|string|max:30',
            'angkatan' => 'nullable|string|max:10',
            'search' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
            'include' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $includes = array_intersect(
            explode(',', (string) ($validated['include'] ?? '')),
            ['course', 'assistants']
        );

        // Selalu load course + assistants agar respons konsisten,
        // kecuali client eksplisit membatasi via ?include=
        $with = $request->has('include') ? array_values($includes) : ['course', 'assistants'];

        $query = Schedule::with($with)
            ->when(isset($validated['course_id']), fn ($q) => $q->where('course_id', $validated['course_id']))
            ->when(isset($validated['hari']), fn ($q) => $q->where('hari', $validated['hari']))
            ->when(isset($validated['program']), fn ($q) => $q->where('program', $validated['program']))
            ->when(isset($validated['angkatan']), fn ($q) => $q->where('angkatan', $validated['angkatan']))
            ->when(isset($validated['is_active']), fn ($q) => $q->where('is_active', (bool) $validated['is_active']))
            ->when(! empty($validated['search']), function ($q) use ($validated) {
                $s = '%' . $validated['search'] . '%';
                $q->where(fn ($w) => $w->where('kelas', 'like', $s)
                    ->orWhere('dosen', 'like', $s)
                    ->orWhere('ruangan', 'like', $s)
                    ->orWhere('program', 'like', $s));
            })
            ->orderedByDay()
            ->orderBy('waktu_mulai');

        $schedules = $query->paginate($validated['per_page'] ?? 15)->withQueryString();

        return ScheduleResource::collection($schedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'angkatan' => 'required|string|max:10',
            'program' => 'required|string|max:30',
            'kelas' => 'required|string|max:30',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'required|string|max:10',
            'waktu_selesai' => 'required|string|max:10',
            'dosen' => 'required|string|max:255',
            'ruangan' => 'nullable|string|max:30',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string',
            'assistant_ids' => 'nullable|array',
            'assistant_ids.*' => 'exists:assistants,id',
            'assistants' => 'nullable|array',
            'assistants.*' => 'exists:assistants,id',
        ]);

        $schedule = Schedule::create($validated);
        $schedule->assistants()->sync($validated['assistant_ids'] ?? $validated['assistants'] ?? []);
        $schedule = $schedule->fresh(['course', 'assistants']);

        return (new ScheduleResource($schedule))
            ->additional(['message' => 'Jadwal berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Schedule $schedule)
    {
        $schedule->load(['course', 'assistants']);

        return new ScheduleResource($schedule);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'course_id' => 'sometimes|required|exists:courses,id',
            'angkatan' => 'sometimes|required|string|max:10',
            'program' => 'sometimes|required|string|max:30',
            'kelas' => 'sometimes|required|string|max:30',
            'hari' => 'sometimes|required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'waktu_mulai' => 'sometimes|required|string|max:10',
            'waktu_selesai' => 'sometimes|required|string|max:10',
            'dosen' => 'sometimes|required|string|max:255',
            'ruangan' => 'nullable|string|max:30',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string',
            'assistant_ids' => 'nullable|array',
            'assistant_ids.*' => 'exists:assistants,id',
            'assistants' => 'nullable|array',
            'assistants.*' => 'exists:assistants,id',
        ]);

        $schedule->update($validated);

        if (array_key_exists('assistant_ids', $validated) || array_key_exists('assistants', $validated)) {
            $schedule->assistants()->sync($validated['assistant_ids'] ?? $validated['assistants'] ?? []);
        }

        $schedule->load(['course', 'assistants']);

        return (new ScheduleResource($schedule))
            ->additional(['message' => 'Jadwal berhasil diperbarui.']);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }
}
