<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = ['parent_id','label','url','route_name','column','sort_order','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean','column'=>'integer','sort_order'=>'integer']; }
    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('column')->orderBy('sort_order'); }
    public function resolvedUrl(): string
    {
        if ($this->route_name && \Illuminate\Support\Facades\Route::has($this->route_name)) return route($this->route_name);
        return $this->url ?: '#';
    }
}
