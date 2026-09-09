<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['order_number', 'customer_name', 'email', 'phone', 'status', 'payment_status', 'subtotal', 'discount_amount', 'shipping_amount', 'total', 'coupon_code', 'shipping_address', 'notes', 'payment_authority', 'payment_ref_id', 'paid_at'];

    protected function casts(): array
    {
        return ['subtotal' => 'integer', 'discount_amount' => 'integer', 'shipping_amount' => 'integer', 'total' => 'integer', 'shipping_address' => 'array', 'paid_at' => 'datetime'];
    }

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
