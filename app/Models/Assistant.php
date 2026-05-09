<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assistant extends Model
{
    protected $fillable = ['name', 'nim', 'phone'];

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'assistant_schedule');
    }
}
