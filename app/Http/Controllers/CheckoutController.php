<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\PricingService;
use App\Services\ZarinpalGateway;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CheckoutController extends Controller
{
    public function index(Request $request, PricingService $pricing): View|RedirectResponse
    {
        $summary = $this->summary($request, $pricing);
        if ($summary['items']->isEmpty()) return redirect()->route('catalog.index');
        return view('checkout.index', $summary);
    }

    public function store(Request $request, PricingService $pricing, ZarinpalGateway $gateway): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:190'],
            'province' => ['required', 'string', 'max:80'],
            'city' => ['required', 'string', 'max:80'],
            'address' => ['required', 'string', 'max:500'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $summary = $this->summary($request, $pricing);
        abort_if($summary['items']->isEmpty(), 422, 'سبد خرید خالی است.');

        try {
            $order = DB::transaction(function () use ($validated, $summary): Order {
                $order = Order::create([
                    'order_number' => 'CRP-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                    'customer_name' => $validated['customer_name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'] ?? null,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'subtotal' => $summary['subtotal'],
                    'discount_amount' => $summary['discount'],
                    'shipping_amount' => $summary['shipping'],
                    'total' => $summary['total'],
                    'coupon_code' => $summary['couponCode'],
                    'shipping_address' => [
                        'province' => $validated['province'],
                        'city' => $validated['city'],
                        'address' => $validated['address'],
                        'postal_code' => $validated['postal_code'] ?? null,
                    ],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($summary['items'] as $item) {
                    $product = Product::query()->lockForUpdate()->findOrFail($item['product']->id);
                    abort_if($product->stock < $item['quantity'], 422, 'موجودی یکی از محصولات تغییر کرده است.');
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unitPrice'],
                        'total_price' => $item['total'],
                        'meta' => ['width' => $product->width, 'height' => $product->height],
                    ]);
                }

                return $order;
            });

            $payment = $gateway->request($order);
            $order->update(['payment_authority' => $payment['authority']]);
            $request->session()->put('pending_order_id', $order->id);
            return redirect()->away($payment['redirect_url']);
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->withErrors(['payment' => 'ایجاد پرداخت انجام نشد. لطفاً دوباره تلاش کنید.']);
        }
    }

    private function summary(Request $request, PricingService $pricing): array
    {
        $cart = collect($request->session()->get('cart', []));
        $products = Product::query()->whereIn('id', $cart->keys())->get()->keyBy('id');
        $items = $cart->map(function (array $row, int|string $productId) use ($products, $pricing) {
            $product = $products->get((int) $productId);
            if (!$product || !$product->is_active || $product->stock < 1) return null;
            $quantity = max(1, min((int) $row['quantity'], $product->stock));
            $unitPrice = $pricing->productPrice($product);
            return compact('product', 'quantity', 'unitPrice') + ['total' => $unitPrice * $quantity];
        })->filter()->values();
        $subtotal = (int) $items->sum('total');
        $couponCode = $request->session()->get('coupon_code');
        $coupon = $pricing->coupon($couponCode, $subtotal);
        $shipping = (int) config('store.shipping_flat', 0);
        $discount = (int) $coupon['discount'];

        return compact('items', 'subtotal', 'discount', 'shipping', 'couponCode') + [
            'total' => max(0, $subtotal - $discount + $shipping),
        ];
    }
}
