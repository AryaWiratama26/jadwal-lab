<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $courses = Course::withCount('schedules')
            ->when(! empty($validated['search']), function ($q) use ($validated) {
                $s = '%' . $validated['search'] . '%';
                $q->where(fn ($w) => $w->where('name', 'like', $s)->orWhere('code', 'like', $s));
            })
            ->orderBy('name')
            ->paginate($validated['per_page'] ?? 15)
            ->withQueryString();

        return CourseResource::collection($courses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20',
            'color' => 'required|string|max:7',
            'semester' => 'nullable|string|max:50',
        ]);

        $course = Course::create($validated);

        return (new CourseResource($course))
            ->additional(['message' => 'Mata kuliah berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Course $course)
    {
        $course->loadCount('schedules');

        return new CourseResource($course);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:20',
            'color' => 'sometimes|required|string|max:7',
            'semester' => 'nullable|string|max:50',
        ]);

        $course->update($validated);

        return (new CourseResource($course))
            ->additional(['message' => 'Mata kuliah berhasil diperbarui.']);
    }

    public function destroy(Course $course)
    {
        if ($course->schedules()->count() > 0) {
            return response()->json([
                'message' => 'Tidak bisa hapus mata kuliah yang masih memiliki ' . $course->schedules()->count() . ' jadwal. Hapus jadwalnya terlebih dahulu.',
            ], 422);
        }

        $course->delete();

        return response()->json(['message' => 'Mata kuliah berhasil dihapus.']);
    }
}
