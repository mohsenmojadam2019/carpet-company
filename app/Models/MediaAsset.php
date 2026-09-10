<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaAsset extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['title'];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('library')->useDisk(config('media-library.disk_name', 'public'));
    }
}
