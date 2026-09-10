<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutPaymentInvoiceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_storefront_payment_invoice_and_admin_order_flow(): void
    {
        $this->seed(DatabaseSeeder::class);
        config(['services.zarinpal.sandbox'=>true,'services.kavenegar.sandbox'=>true]);
        Setting::put('zarinpal_sandbox',true,'payments','boolean',false);
        Setting::put('kavenegar_sandbox',true,'notifications','boolean',false);
        Setting::put('zarinpal_merchant_id','sandbox-test-merchant','payments','encrypted',false);
        Http::preventStrayRequests();
        Http::fake(function(Request $request){
            if(str_ends_with($request->url(),'/pg/v4/payment/request.json'))return Http::response(['data'=>['code'=>100,'authority'=>'A000000000000000000000000000000001']],200);
            if(str_ends_with($request->url(),'/pg/v4/payment/verify.json'))return Http::response(['data'=>['code'=>100,'ref_id'=>987654321,'card_pan'=>'6037-****-****-1234']],200);
            return Http::response([],500);
        });
        $product=Product::where('stock','>',2)->firstOrFail();$stockBefore=$product->stock;$coupon=Coupon::where('code','WELCOME10')->firstOrFail();$couponUsedBefore=$coupon->used_count;$token='checkout-e2e-token';
        $response=$this->withSession(['_token'=>$token,'cart'=>[$product->id=>['quantity'=>1]],'coupon_code'=>'WELCOME10'])->post(route('checkout.store'),['_token'=>$token,'customer_name'=>'مشتری تست کامل','phone'=>'09120009999','email'=>'e2e@example.test','province'=>'تهران','city'=>'تهران','address'=>'خیابان تست، پلاک ۱','postal_code'=>'1234567890','notes'=>'تست E2E']);
        $response->assertRedirect('https://sandbox.zarinpal.com/pg/StartPay/A000000000000000000000000000000001')->assertSessionHas('pending_order_id');
        $order=Order::where('phone','09120009999')->latest()->firstOrFail();
        $this->assertSame('pending',$order->payment_status);$this->assertNotEmpty($order->public_token);$this->assertDatabaseHas('order_status_histories',['order_id'=>$order->id,'to_status'=>'pending']);
        $callback=$this->get(route('payment.zarinpal.callback',['Authority'=>'A000000000000000000000000000000001','Status'=>'OK']));$order->refresh();
        $callback->assertRedirect(route('orders.success',['order'=>$order->public_token]));
        $this->assertSame('paid',$order->payment_status);$this->assertSame('processing',$order->status);$this->assertNotEmpty($order->invoice_number);$this->assertSame(987654321,(int)$order->payment_ref_id);$this->assertSame($stockBefore-1,$product->fresh()->stock);$this->assertSame($couponUsedBefore+1,$coupon->fresh()->used_count);$this->assertDatabaseHas('order_status_histories',['order_id'=>$order->id,'to_status'=>'processing']);
        $this->get(route('orders.success',['order'=>$order->public_token]))->assertOk()->assertSee($order->order_number)->assertSee($order->invoice_number);
        $this->get(route('orders.invoice',['order'=>$order->public_token]))->assertOk()->assertSee('فاکتور فروش')->assertSee($order->invoice_number)->assertSee($product->name);
        $admin=User::where('email','admin@carpet.local')->firstOrFail();$this->actingAs($admin)->get(route('admin.orders.show',$order))->assertOk()->assertSee($order->invoice_number)->assertSee('تاریخچه سفارش');
        $adminToken='admin-order-token';$this->withSession(['_token'=>$adminToken])->patch(route('admin.orders.update',$order),['_token'=>$adminToken,'status'=>'packed','status_note'=>'بسته‌بندی تست E2E','notes'=>'آماده ارسال'])->assertRedirect();
        $this->assertDatabaseHas('order_status_histories',['order_id'=>$order->id,'from_status'=>'processing','to_status'=>'packed','actor_id'=>$admin->id]);$this->assertSame('packed',$order->fresh()->status);
        Http::assertSentCount(2);
    }

    public function test_demo_seed_has_broad_catalog_orders_and_local_admin(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->assertGreaterThanOrEqual(8,Category::count());$this->assertGreaterThanOrEqual(40,Product::count());$this->assertGreaterThanOrEqual(12,Order::count());$this->assertNotNull(User::where('email','admin@carpet.local')->first());
        foreach(['handmade','machine-made','wall-rug','moquette','carpet-tile','rug','runner','kilim'] as $slug){$category=Category::where('slug',$slug)->firstOrFail();$this->assertGreaterThanOrEqual(5,$category->products()->count());}
    }
}
