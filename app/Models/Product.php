<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'sale_price', 'stock', 'width', 'height', 'material', 'weave',
        'density', 'origin', 'colors', 'images', 'variants', 'specifications',
        'featured', 'is_active', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer', 'sale_price' => 'integer', 'stock' => 'integer',
            'colors' => 'array', 'images' => 'array', 'variants' => 'array',
            'specifications' => 'array', 'featured' => 'boolean', 'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }

    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true); }
    public function scopeFeatured(Builder $query): Builder { return $query->where('featured', true); }

    public function getFinalPriceAttribute(): int
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }

    public function getPrimaryImageAttribute(): string
    {
        return $this->images[0] ?? '/images/rug-01.svg';
    }
}
