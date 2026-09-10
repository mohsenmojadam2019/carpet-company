<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number','public_token','invoice_number','customer_name','email','phone','status','payment_status','payment_gateway',
        'subtotal','discount_amount','shipping_amount','total','coupon_code','shipping_address','notes','payment_authority','payment_ref_id',
        'paid_at','shipped_at','completed_at','cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'=>'integer','discount_amount'=>'integer','shipping_amount'=>'integer','total'=>'integer','shipping_address'=>'array',
            'paid_at'=>'datetime','shipped_at'=>'datetime','completed_at'=>'datetime','cancelled_at'=>'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            $order->public_token ??= Str::random(48);
            $order->payment_gateway ??= 'zarinpal';
        });
    }

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
    public function statusHistories(): HasMany { return $this->hasMany(OrderStatusHistory::class)->latest(); }

    public function transitionTo(string $status, ?string $note = null, ?int $actorId = null, array $meta = []): void
    {
        $from = $this->status;
        if ($from === $status && !$note) return;

        $timestamps = match ($status) {
            'shipped' => ['shipped_at' => $this->shipped_at ?: now()],
            'completed' => ['completed_at' => $this->completed_at ?: now()],
            'cancelled', 'refunded' => ['cancelled_at' => $this->cancelled_at ?: now()],
            default => [],
        };

        $this->update(['status' => $status] + $timestamps);
        $this->statusHistories()->create([
            'actor_id'=>$actorId,'from_status'=>$from,'to_status'=>$status,'note'=>$note,'meta'=>$meta ?: null,
        ]);
    }

    public function ensureInvoiceNumber(): string
    {
        if ($this->invoice_number) return $this->invoice_number;
        $this->invoice_number = 'INV-'.now()->format('ymd').'-'.str_pad((string)$this->id, 6, '0', STR_PAD_LEFT);
        $this->save();
        return $this->invoice_number;
    }
}
