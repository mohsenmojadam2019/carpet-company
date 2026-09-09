<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'sku', 'quantity', 'unit_price', 'total_price', 'meta'];
    protected function casts(): array { return ['quantity' => 'integer', 'unit_price' => 'integer', 'total_price' => 'integer', 'meta' => 'array']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
