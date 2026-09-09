<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'type', 'value', 'min_order', 'max_discount', 'usage_limit', 'used_count', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return ['value' => 'integer', 'min_order' => 'integer', 'max_discount' => 'integer', 'usage_limit' => 'integer', 'used_count' => 'integer', 'starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean'];
    }

    public function isValidFor(int $subtotal): bool
    {
        return $this->is_active
            && (!$this->starts_at || $this->starts_at->isPast())
            && (!$this->ends_at || $this->ends_at->isFuture())
            && (!$this->usage_limit || $this->used_count < $this->usage_limit)
            && $subtotal >= $this->min_order;
    }

    public function discountFor(int $subtotal): int
    {
        if (!$this->isValidFor($subtotal)) return 0;
        $discount = $this->type === 'percent' ? (int) floor($subtotal * min($this->value, 100) / 100) : min($this->value, $subtotal);
        return $this->max_discount ? min($discount, $this->max_discount) : $discount;
    }
}
