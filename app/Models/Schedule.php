<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    protected $fillable = [
        'course_id',
        'angkatan',
        'program',
        'kelas',
        'hari',
        'waktu_mulai',
        'waktu_selesai',
        'dosen',
        'ruangan',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function assistants(): BelongsToMany
    {
        return $this->belongsToMany(Assistant::class, 'assistant_schedule');
    }

    /**
     * Get formatted waktu string like "09.30 - 11.30"
     */
    public function getWaktuAttribute(): string
    {
        return $this->waktu_mulai . ' - ' . $this->waktu_selesai;
    }

    /**
     * Order days in Indonesian weekday order
     */
    public static function dayOrder(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    }
}
