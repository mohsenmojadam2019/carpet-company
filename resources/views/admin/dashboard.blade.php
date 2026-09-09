@extends('layouts.admin')
@section('title','داشبورد مدیریت | خانه فرش')
@section('content')
<div class="admin-top"><div><small style="color:#948d84">امروز {{ now()->format('Y/m/d') }}</small><h1>داشبورد فروشگاه</h1></div><a class="btn btn-primary" href="{{ route('home') }}" target="_blank" rel="noopener">مشاهده سایت</a></div>
<div class="metric-grid">
    <div class="metric"><small>فروش پرداخت‌شده</small><b>{{ number_format($metrics['revenue']) }}</b><span style="font-size:11px;color:#999">تومان</span></div>
    <div class="metric"><small>کل سفارش‌ها</small><b>{{ number_format($metrics['orders']) }}</b><span style="font-size:11px;color:#999">سفارش</span></div>
    <div class="metric"><small>محصولات</small><b>{{ number_format($metrics['products']) }}</b><span style="font-size:11px;color:#999">محصول</span></div>
    <div class="metric"><small>موجودی کم</small><b>{{ number_format($metrics['lowStock']) }}</b><span style="font-size:11px;color:#999">نیازمند بررسی</span></div>
</div>
<div class="admin-grid">
    <section class="admin-panel" id="orders"><div style="display:flex;justify-content:space-between;align-items:center"><div><small class="eyebrow">RECENT ORDERS</small><h2 style="font-size:20px;margin:8px 0 16px">آخرین سفارش‌ها</h2></div></div><div style="overflow:auto"><table class="admin-table"><thead><tr><th>شماره</th><th>مشتری</th><th>مبلغ</th><th>پرداخت</th><th>وضعیت</th><th>تاریخ</th></tr></thead><tbody>@forelse($recentOrders as $order)<tr><td>{{ $order->order_number }}</td><td>{{ $order->customer_name }}</td><td>{{ number_format($order->total) }}</td><td><span class="status-pill">{{ $order->payment_status }}</span></td><td>{{ $order->status }}</td><td>{{ $order->created_at?->format('Y/m/d H:i') }}</td></tr>@empty<tr><td colspan="6" style="text-align:center;color:#999">هنوز سفارشی ثبت نشده است.</td></tr>@endforelse</tbody></table></div></section>
    <aside class="admin-panel"><small class="eyebrow">TOP PRODUCTS</small><h2 style="font-size:20px;margin:8px 0 16px">پرفروش‌ها</h2>@forelse($topProducts as $product)<div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1eee8;padding:11px 0;font-size:12px"><span>{{ $product->product_name }}</span><b>{{ number_format($product->sold) }} فروش</b></div>@empty<p style="font-size:12px;color:#999">پس از ثبت سفارش، آمار این بخش نمایش داده می‌شود.</p>@endforelse</aside>
</div>
<div class="admin-panel" style="margin-top:18px"><small class="eyebrow">OPERATIONS</small><h2 style="font-size:20px;margin:8px 0 18px">دسترسی سریع</h2><div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px"><a class="btn btn-ghost" href="#products">محصول جدید</a><a class="btn btn-ghost" href="#discounts">کد تخفیف</a><a class="btn btn-ghost" href="#projects">پروژه جدید</a><a class="btn btn-ghost" href="#settings">تنظیمات درگاه</a></div></div>
@endsection
