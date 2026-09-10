<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
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
        $orderId=(int)$request->session()->get('pending_order_id');
        $authority=(string)$request->query('Authority','');
        $status=strtoupper((string)$request->query('Status',''));
        $order=Order::query()->with('items')->find($orderId);
        if(!$order||!$authority||$status!=='OK')return redirect()->route('cart.index')->withErrors(['payment'=>'پرداخت لغو شد یا اطلاعات تراکنش معتبر نیست.']);
        if($order->payment_authority&&!hash_equals((string)$order->payment_authority,$authority))return redirect()->route('cart.index')->withErrors(['payment'=>'شناسه تراکنش با سفارش مطابقت ندارد.']);
        if($order->payment_status==='paid')return redirect()->route('orders.success',['order'=>$order->public_token]);
        try{
            $verified=$gateway->verify($order,$authority);
            $inventoryReview=DB::transaction(function()use($order,$verified,$authority):bool{
                $locked=Order::query()->with('items')->lockForUpdate()->findOrFail($order->id);
                if($locked->payment_status==='paid')return $locked->status==='inventory_review';
                $inventoryReview=false;
                foreach($locked->items as $item){$product=Product::query()->lockForUpdate()->find($item->product_id);if(!$product||$product->stock<$item->quantity){$inventoryReview=true;continue;}$product->decrement('stock',$item->quantity);}
                if($locked->coupon_code){$coupon=Coupon::query()->whereRaw('UPPER(code)=?',[mb_strtoupper($locked->coupon_code)])->lockForUpdate()->first();if($coupon)$coupon->increment('used_count');}
                $newStatus=$inventoryReview?'inventory_review':'processing';$from=$locked->status;
                $locked->update(['status'=>$newStatus,'payment_status'=>'paid','payment_authority'=>$authority,'payment_ref_id'=>$verified['ref_id'],'paid_at'=>now(),'invoice_number'=>'INV-'.now()->format('ymd').'-'.str_pad((string)$locked->id,6,'0',STR_PAD_LEFT)]);
                $locked->statusHistories()->create(['from_status'=>$from,'to_status'=>$newStatus,'note'=>$inventoryReview?'پرداخت تأیید شد؛ سفارش نیازمند بررسی موجودی است.':'پرداخت زرین‌پال تأیید شد و سفارش وارد پردازش شد.','meta'=>['payment_ref_id'=>$verified['ref_id'],'gateway'=>'zarinpal']]);
                return $inventoryReview;
            });
            $request->session()->forget(['cart','coupon_code','pending_order_id']);
            $order->refresh();
            try{$sms->send($order->phone,"سفارش {$order->order_number} با موفقیت پرداخت شد. شماره فاکتور: {$order->invoice_number}");}catch(Throwable $smsError){report($smsError);}
            return redirect()->route('orders.success',['order'=>$order->public_token])->with('success',$inventoryReview?'پرداخت موفق بود؛ سفارش برای بررسی موجودی ثبت شد.':'پرداخت موفق بود و فاکتور شما صادر شد.');
        }catch(Throwable $e){report($e);return redirect()->route('cart.index')->withErrors(['payment'=>'تأیید پرداخت انجام نشد. در صورت کسر وجه، وضعیت سفارش بررسی خواهد شد.']);}
    }
}
