<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

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
                'encrypted' => $setting->value ? Crypt::decryptString((string) $setting->value) : '',
                default => $setting->value,
            };
        });
    }

    public static function put(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $public = true): void
    {
        $stored = match ($type) {
            'json' => json_encode($value, JSON_UNESCAPED_UNICODE),
            'encrypted' => $value === null || $value === '' ? '' : Crypt::encryptString((string) $value),
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOL) ? '1' : '0',
            default => (string) $value,
        };
        static::query()->updateOrCreate(['key'=>$key], ['group'=>$group,'value'=>$stored,'type'=>$type,'is_public'=>$public]);
        Cache::forget("setting:{$key}");
    }
}
