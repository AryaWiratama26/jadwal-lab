<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('schedules')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'color' => 'required|string|max:7',
            'semester' => 'nullable|string|max:50',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'color' => 'required|string|max:7',
            'semester' => 'nullable|string|max:50',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        if ($course->schedules()->count() > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Tidak bisa hapus mata kuliah yang masih memiliki ' . $course->schedules()->count() . ' jadwal aktif. Hapus jadwalnya terlebih dahulu.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil dihapus!');
    }
}
