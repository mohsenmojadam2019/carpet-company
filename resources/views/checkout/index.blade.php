@extends('layouts.app')
@section('title', 'تسویه حساب | خانه فرش')
@section('meta_description', 'ثبت مشخصات ارسال و پرداخت امن سفارش از طریق زرین‌پال.')
@section('content')
<section class="simple-page container">
    <span class="eyebrow">SECURE CHECKOUT</span><h1>تسویه حساب</h1>
    <div class="checkout-layout">
        <form class="checkout-form" method="post" action="{{ route('checkout.store') }}">@csrf
            <div class="field"><label for="customer_name">نام و نام خانوادگی</label><input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="name"></div>
            <div class="field"><label for="phone">شماره موبایل</label><input id="phone" name="phone" value="{{ old('phone') }}" required inputmode="tel" autocomplete="tel"></div>
            <div class="field full"><label for="email">ایمیل <span style="color:#aaa">(اختیاری)</span></label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email"></div>
            <div class="field"><label for="province">استان</label><input id="province" name="province" value="{{ old('province') }}" required autocomplete="address-level1"></div>
            <div class="field"><label for="city">شهر</label><input id="city" name="city" value="{{ old('city') }}" required autocomplete="address-level2"></div>
            <div class="field full"><label for="address">آدرس کامل</label><textarea id="address" name="address" rows="4" required autocomplete="street-address">{{ old('address') }}</textarea></div>
            <div class="field"><label for="postal_code">کد پستی</label><input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" autocomplete="postal-code"></div>
            <div class="field"><label for="notes">توضیحات سفارش</label><input id="notes" name="notes" value="{{ old('notes') }}" placeholder="مثلاً هماهنگی قبل از ارسال"></div>
            <div class="field full"><button class="btn btn-primary" type="submit" style="width:100%;margin-top:12px">پرداخت امن با زرین‌پال</button><small style="text-align:center;color:#8c867d">پس از ثبت، به درگاه امن زرین‌پال منتقل می‌شوید.</small></div>
        </form>
        <aside class="summary-card"><h2 style="margin-top:0;font-size:22px">مرور سفارش</h2>@foreach($items as $item)<div class="summary-row"><span>{{ $item['product']->name }} × {{ $item['quantity'] }}</span><b>{{ number_format($item['total']) }}</b></div>@endforeach<div class="summary-row"><span>جمع محصولات</span><b>{{ number_format($subtotal) }}</b></div><div class="summary-row"><span>تخفیف</span><b>{{ $discount ? '-'.number_format($discount) : '—' }}</b></div><div class="summary-row"><span>ارسال</span><b>{{ $shipping ? number_format($shipping) : 'رایگان' }}</b></div><div class="summary-row total"><span>مبلغ نهایی</span><b>{{ number_format($total) }} تومان</b></div><p style="font-size:11px;color:#8b857b;margin-bottom:0">اطلاعات پرداخت در سایت ذخیره نمی‌شود و تراکنش مستقیماً در زرین‌پال انجام می‌شود.</p></aside>
    </div>
</section>
@endsection
