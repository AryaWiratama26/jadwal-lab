<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'angkatan' => $this->angkatan,
            'program' => $this->program,
            'kelas' => $this->kelas,
            'hari' => $this->hari,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'waktu' => $this->waktu,
            'dosen' => $this->dosen,
            'ruangan' => $this->ruangan,
            'is_active' => (bool) $this->is_active,
            'catatan' => $this->catatan,
            'course' => new CourseResource($this->whenLoaded('course')),
            'assistants' => AssistantResource::collection($this->whenLoaded('assistants')),
            'assistant_ids' => $this->when(
                $this->relationLoaded('assistants'),
                fn () => $this->assistants->pluck('id')->values()->all()
            ),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
