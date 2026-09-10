@extends('layouts.app')
@section('title','علاقه‌مندی‌ها | خانه فرش')
@section('meta_description','محصولات ذخیره‌شده برای بررسی و خرید بعدی.')
@section('content')
<section class="section"><div class="container"><div class="section-head"><div><span class="eyebrow">WISHLIST</span><h1>علاقه‌مندی‌ها</h1></div><p>{{ $products->count() }} محصول ذخیره‌شده در این مرورگر.</p></div><div class="product-grid">@forelse($products as $product)<article class="product-card"><a href="{{ route('products.show',$product) }}"><div class="product-image"><img src="{{ $product->primary_image }}" alt="{{ $product->name }}" loading="lazy" width="600" height="760"></div><h2>{{ $product->name }}</h2><div class="price">{{ number_format($product->final_price) }} تومان</div></a><form method="post" action="{{ route('wishlist.toggle',$product) }}">@csrf<button class="text-link">حذف از علاقه‌مندی‌ها</button></form></article>@empty<div class="empty-state"><h2>لیست علاقه‌مندی خالی است.</h2><a class="btn" href="{{ route('catalog.index') }}">مشاهده فروشگاه</a></div>@endforelse</div></div></section>
@endsection
