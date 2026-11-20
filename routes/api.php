<?php

use App\Http\Controllers\Api\V1\AssistantController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api.key', 'throttle:60,1'])->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'message' => 'OK',
            'data' => [
                'version' => 'v1',
                'time' => now()->toIso8601String(),
            ],
        ]);
    });

    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('assistants', AssistantController::class);
});
