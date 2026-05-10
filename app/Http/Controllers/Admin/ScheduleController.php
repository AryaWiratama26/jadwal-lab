<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $schedules = Schedule::with(['course', 'assistants'])
            ->when($request->course_id, fn($q, $v) => $q->where('course_id', $v))
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('waktu_mulai')
            ->paginate(15);

        $courses = Course::all();

        return view('admin.schedules.index', compact('schedules', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        $assistants = Assistant::all();

        return view('admin.schedules.create', compact('courses', 'assistants'));
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
            'assistants' => 'nullable|array',
            'assistants.*' => 'exists:assistants,id',
        ]);

        $schedule = Schedule::create($validated);

        if ($request->has('assistants')) {
            $schedule->assistants()->sync($request->assistants);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule)
    {
        $schedule->load('assistants');
        $courses = Course::all();
        $assistants = Assistant::all();

        return view('admin.schedules.edit', compact('schedule', 'courses', 'assistants'));
    }

    public function update(Request $request, Schedule $schedule)
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
            'assistants' => 'nullable|array',
            'assistants.*' => 'exists:assistants,id',
        ]);

        $schedule->update($validated);
        $schedule->assistants()->sync($request->assistants ?? []);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
