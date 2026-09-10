<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['category_id','name','slug','sku','short_description','description','price','sale_price','stock','width','height','material','weave','density','origin','colors','images','variants','specifications','featured','is_active','meta_title','meta_description'];

    protected function casts(): array
    {
        return ['price'=>'integer','sale_price'=>'integer','stock'=>'integer','colors'=>'array','images'=>'array','variants'=>'array','specifications'=>'array','featured'=>'boolean','is_active'=>'boolean'];
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true); }
    public function scopeFeatured(Builder $query): Builder { return $query->where('featured', true); }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')->useDisk(config('media-library.disk_name', 'public'));
    }

    public function getFinalPriceAttribute(): int
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }

    public function getPrimaryImageAttribute(): string
    {
        $media = $this->getFirstMedia('gallery');
        if ($media instanceof Media) return $media->getUrl();
        return $this->images[0] ?? '/images/rug-01.svg';
    }
}
