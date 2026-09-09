@extends('layouts.app')
@section('title', 'سبد خرید | خانه فرش')
@section('meta_description', 'مرور محصولات انتخاب‌شده، اعمال کد تخفیف و ادامه خرید امن.')
@section('content')
<section class="simple-page container">
    <span class="eyebrow">YOUR SELECTION</span><h1>سبد خرید</h1>
    @if($items->isEmpty())
        <div style="padding:90px 0;text-align:center;background:#fbfaf7"><h2>سبد شما خالی است.</h2><p style="color:#777">برای دیدن کالکشن‌ها به فروشگاه برگردید.</p><a class="btn btn-primary" href="{{ route('catalog.index') }}">ورود به فروشگاه</a></div>
    @else
        <div class="cart-layout">
            <div>
                @foreach($items as $item)
                    <article class="cart-item">
                        <a class="cart-thumb" href="{{ route('products.show',$item['product']) }}"><img src="{{ $item['product']->primary_image }}" width="180" height="220" alt="{{ $item['product']->name }}"></a>
                        <div><h3><a href="{{ route('products.show',$item['product']) }}">{{ $item['product']->name }}</a></h3><small>{{ $item['product']->sku }}</small><form method="post" action="{{ route('cart.update',$item['product']) }}" style="display:flex;gap:8px;margin-top:12px">@csrf @method('PATCH')<input class="qty" style="height:40px" type="number" name="quantity" min="1" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}"><button class="text-link" style="background:none;border:0;cursor:pointer" type="submit">به‌روزرسانی</button></form><form method="post" action="{{ route('cart.destroy',$item['product']) }}" style="margin-top:6px">@csrf @method('DELETE')<button style="border:0;background:none;color:#9c5f57;font-size:11px;cursor:pointer;padding:0" type="submit">حذف از سبد</button></form></div>
                        <strong>{{ number_format($item['total']) }} تومان</strong>
                    </article>
                @endforeach
            </div>
            <aside class="summary-card"><h2 style="margin-top:0;font-size:22px">خلاصه سفارش</h2><div class="summary-row"><span>جمع محصولات</span><b>{{ number_format($subtotal) }}</b></div><div class="summary-row"><span>تخفیف</span><b>{{ $discount ? '-'.number_format($discount) : '—' }}</b></div><form class="coupon-form" method="post" action="{{ route('cart.coupon') }}">@csrf<input type="text" name="code" value="{{ $couponCode }}" placeholder="کد تخفیف"><button type="submit">اعمال</button></form><div class="summary-row total"><span>مبلغ قابل پرداخت</span><b>{{ number_format($total) }} تومان</b></div><a class="btn btn-primary" style="width:100%;margin-top:18px" href="{{ route('checkout.index') }}">ادامه و ثبت سفارش</a><a class="btn btn-ghost" style="width:100%;margin-top:8px" href="{{ route('catalog.index') }}">ادامه خرید</a></aside>
        </div>
    @endif
</section>
@endsection
