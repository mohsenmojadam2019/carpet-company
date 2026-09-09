<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\KavenegarSms;
use App\Services\ZarinpalGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentController extends Controller
{
    public function __invoke(Request $request, ZarinpalGateway $gateway, KavenegarSms $sms): RedirectResponse
    {
        $orderId = (int) $request->session()->get('pending_order_id');
        $authority = (string) $request->query('Authority', '');
        $status = strtoupper((string) $request->query('Status', ''));
        $order = Order::query()->with('items')->find($orderId);

        if (!$order || !$authority || $status !== 'OK') {
            return redirect()->route('cart.index')->withErrors(['payment' => 'پرداخت لغو شد یا اطلاعات تراکنش معتبر نیست.']);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('home')->with('success', 'سفارش شما قبلاً با موفقیت پرداخت شده است.');
        }

        try {
            $verified = $gateway->verify($order, $authority);

            DB::transaction(function () use ($order, $verified): void {
                foreach ($order->items as $item) {
                    $product = Product::query()->lockForUpdate()->find($item->product_id);
                    if ($product && $product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'payment_authority' => request()->query('Authority'),
                    'payment_ref_id' => $verified['ref_id'],
                    'paid_at' => now(),
                ]);
            });

            $request->session()->forget(['cart', 'coupon_code', 'pending_order_id']);

            try {
                $sms->send($order->phone, "سفارش {$order->order_number} با موفقیت ثبت و پرداخت شد.");
            } catch (Throwable $smsError) {
                report($smsError);
            }

            return redirect()->route('home')->with('success', 'پرداخت موفق بود. سفارش شما وارد مرحله پردازش شد.');
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('cart.index')->withErrors(['payment' => 'تأیید پرداخت انجام نشد. در صورت کسر وجه، وضعیت سفارش بررسی خواهد شد.']);
        }
    }
}
