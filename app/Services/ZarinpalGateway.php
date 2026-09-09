<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ZarinpalGateway
{
    public function request(Order $order): array
    {
        $response = $this->client()->post($this->apiBase().'/pg/v4/payment/request.json', [
            'merchant_id' => $this->merchantId(),
            'amount' => $this->gatewayAmount($order->total),
            'callback_url' => config('services.zarinpal.callback_url') ?: route('payment.zarinpal.callback'),
            'description' => 'پرداخت سفارش '.$order->order_number,
            'metadata' => ['mobile' => $order->phone, 'email' => $order->email],
        ])->throw()->json();

        $authority = data_get($response, 'data.authority');
        if (!$authority || (int) data_get($response, 'data.code') !== 100) {
            throw new RuntimeException(data_get($response, 'errors.message', 'خطا در ایجاد تراکنش زرین‌پال.'));
        }

        return ['authority' => $authority, 'redirect_url' => $this->startPayBase().'/'.$authority];
    }

    public function verify(Order $order, string $authority): array
    {
        $response = $this->client()->post($this->apiBase().'/pg/v4/payment/verify.json', [
            'merchant_id' => $this->merchantId(),
            'amount' => $this->gatewayAmount($order->total),
            'authority' => $authority,
        ])->throw()->json();

        $code = (int) data_get($response, 'data.code', 0);
        if (!in_array($code, [100, 101], true)) {
            throw new RuntimeException(data_get($response, 'errors.message', 'پرداخت تایید نشد.'));
        }

        return ['code' => $code, 'ref_id' => (string) data_get($response, 'data.ref_id'), 'card_pan' => data_get($response, 'data.card_pan')];
    }

    private function client(): PendingRequest { return Http::acceptJson()->asJson()->timeout(12)->retry(2, 250); }
    private function merchantId(): string { return (string) config('services.zarinpal.merchant_id'); }
    private function gatewayAmount(int $tomanAmount): int { return $tomanAmount * (int) config('services.zarinpal.amount_multiplier', 10); }
    private function apiBase(): string { return config('services.zarinpal.sandbox') ? 'https://sandbox.zarinpal.com' : 'https://payment.zarinpal.com'; }
    private function startPayBase(): string { return config('services.zarinpal.sandbox') ? 'https://sandbox.zarinpal.com/pg/StartPay' : 'https://www.zarinpal.com/pg/StartPay'; }
}
