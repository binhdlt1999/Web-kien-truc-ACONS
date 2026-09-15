<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type', 'is_public'];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public static function publicValues(): array
    {
        return Cache::remember('settings.public', now()->addHour(), fn () => static::query()->where('is_public', true)->pluck('value', 'key')->all()
        );
    }

    public static function clearCache(): void
    {
        Cache::forget('settings.public');
    }
}
