<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title','slug','excerpt','content','image','location','year','is_featured','is_active'];
    protected function casts(): array { return ['year'=>'integer','is_featured'=>'boolean','is_active'=>'boolean']; }

    public function registerMediaCollections(): void
    {
        $disk = config('media-library.disk_name', 'public');
        $this->addMediaCollection('cover')->singleFile()->useDisk($disk);
        $this->addMediaCollection('gallery')->useDisk($disk);
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover') ?: ($this->image ?: '/assets/img/atelier.svg');
    }
}
