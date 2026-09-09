<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image', 'location', 'year', 'is_featured', 'is_active'];
    protected function casts(): array { return ['year' => 'integer', 'is_featured' => 'boolean', 'is_active' => 'boolean']; }
}
