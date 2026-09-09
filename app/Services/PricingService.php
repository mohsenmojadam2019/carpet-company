<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\DiscountRule;
use App\Models\Product;

class PricingService
{
    public function productPrice(Product $product): int
    {
        $base = $product->final_price;
        $rules = DiscountRule::query()->where('is_active', true)->orderByDesc('priority')->get();

        foreach ($rules as $rule) {
            if (!$rule->isActiveNow() || !$this->matches($rule, $product)) continue;
            $discount = $rule->type === 'percent' ? (int) floor($base * min($rule->value, 100) / 100) : min($rule->value, $base);
            return max(0, $base - $discount);
        }

        return $base;
    }

    public function coupon(?string $code, int $subtotal): array
    {
        if (!$code) return ['coupon' => null, 'discount' => 0];
        $coupon = Coupon::query()->whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($code))])->first();
        return ['coupon' => $coupon, 'discount' => $coupon?->discountFor($subtotal) ?? 0];
    }

    private function matches(DiscountRule $rule, Product $product): bool
    {
        return match ($rule->scope) {
            'all' => true,
            'product' => in_array($product->id, $rule->scope_value ?? [], true),
            'category' => in_array($product->category_id, $rule->scope_value ?? [], true),
            default => false,
        };
    }
}
