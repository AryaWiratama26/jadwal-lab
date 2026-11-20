<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'token_hash',
        'prefix',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'token_hash',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate a new API key. Returns [plaintext, model].
     * Plaintext is only available at creation time.
     */
    public static function generate(string $name): array
    {
        $plaintext = 'sk_' . Str::random(48);

        $model = static::create([
            'name' => $name,
            'token_hash' => hash('sha256', $plaintext),
            'prefix' => substr($plaintext, 0, 12),
            'is_active' => true,
        ]);

        return [$plaintext, $model];
    }

    public static function findByPlaintext(string $plaintext): ?static
    {
        return static::active()
            ->where('token_hash', hash('sha256', $plaintext))
            ->first();
    }

    public function markUsed(): void
    {
        $this->forceFill(['last_used_at' => now()])->saveQuietly();
    }

    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }
}
