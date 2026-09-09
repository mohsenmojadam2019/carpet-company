<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'scope', 'scope_value', 'value', 'priority', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return ['scope_value' => 'array', 'value' => 'integer', 'priority' => 'integer', 'starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean'];
    }

    public function isActiveNow(): bool
    {
        return $this->is_active && (!$this->starts_at || $this->starts_at->isPast()) && (!$this->ends_at || $this->ends_at->isFuture());
    }
}
