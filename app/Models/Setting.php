<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group','key','value','type','is_public'];
    protected function casts(): array { return ['is_public' => 'boolean']; }

    public static function valueOf(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting:{$key}", 3600, function () use ($key, $default) {
            $setting = static::query()->where('key', $key)->first();
            if (!$setting) return $default;
            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOL),
                'integer' => (int) $setting->value,
                'json' => json_decode((string) $setting->value, true) ?: [],
                default => $setting->value,
            };
        });
    }

    public static function put(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $public = true): void
    {
        $stored = $type === 'json' ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;
        static::query()->updateOrCreate(['key'=>$key], ['group'=>$group,'value'=>$stored,'type'=>$type,'is_public'=>$public]);
        Cache::forget("setting:{$key}");
    }
}
