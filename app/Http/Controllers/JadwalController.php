<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();
        $activeCourseId = $request->get('course', $courses->first()?->id);

        $schedules = Schedule::with(['course', 'assistants'])
            ->where('is_active', true)
            ->when($activeCourseId, fn($q) => $q->where('course_id', $activeCourseId))
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('waktu_mulai')
            ->get();

        $activeCourse = Course::find($activeCourseId);

        return view('jadwal', compact('courses', 'schedules', 'activeCourseId', 'activeCourse'));
    }
}
