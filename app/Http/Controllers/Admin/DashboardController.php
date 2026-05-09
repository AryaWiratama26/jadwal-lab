<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\Course;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSchedules = Schedule::count();
        $activeSchedules = Schedule::where('is_active', true)->count();
        $totalCourses = Course::count();
        $totalAssistants = Assistant::count();

        $recentSchedules = Schedule::with(['course', 'assistants'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSchedules', 'activeSchedules', 'totalCourses', 'totalAssistants', 'recentSchedules'
        ));
    }
}
