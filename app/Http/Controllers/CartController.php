<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, PricingService $pricing): View
    {
        $cart = collect($request->session()->get('cart', []));
        $products = Product::query()->whereIn('id', $cart->keys())->get()->keyBy('id');

        $items = $cart->map(function (array $row, int|string $productId) use ($products, $pricing) {
            $product = $products->get((int) $productId);
            if (!$product) return null;
            $quantity = max(1, min((int) $row['quantity'], max(1, $product->stock)));
            $unitPrice = $pricing->productPrice($product);
            return compact('product', 'quantity', 'unitPrice') + ['total' => $unitPrice * $quantity];
        })->filter()->values();

        $subtotal = (int) $items->sum('total');
        $couponCode = $request->session()->get('coupon_code');
        $coupon = $pricing->coupon($couponCode, $subtotal);

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $coupon['discount'],
            'total' => max(0, $subtotal - $coupon['discount']),
            'couponCode' => $couponCode,
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->stock > 0, 404);
        $quantity = max(1, min((int) $request->input('quantity', 1), $product->stock));
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = ['quantity' => min(($cart[$product->id]['quantity'] ?? 0) + $quantity, $product->stock)];
        $request->session()->put('cart', $cart);

        return back()->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, min((int) $request->input('quantity', 1), max(1, $product->stock)));
        $cart = $request->session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }
        return back();
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);
        return back()->with('success', 'محصول از سبد حذف شد.');
    }

    public function coupon(Request $request): RedirectResponse
    {
        $code = trim((string) $request->input('code'));
        $code ? $request->session()->put('coupon_code', $code) : $request->session()->forget('coupon_code');
        return back();
    }
}
