@extends('layouts.app')

@section('title', 'فروشگاه فرش و قالی | خانه فرش')
@section('meta_description', 'خرید آنلاین فرش، قالی، تابلو فرش، فرشینه و موکت با فیلتر حرفه‌ای بر اساس دسته‌بندی، قیمت، جنس و سبک.')

@section('content')
<section class="page-hero">
    <div class="container">
        <div><span class="eyebrow">THE SHOP</span><h1>فروشگاه</h1></div>
        <p>محصولات را بر اساس فضا، دسته‌بندی و بودجه محدود کنید؛ طراحی این صفحه عمداً ساده است تا خودِ بافت و رنگ فرش مرکز توجه بماند.</p>
    </div>
</section>

<section class="container catalog-layout">
    <aside class="filters" aria-label="فیلتر محصولات">
        <form method="get" action="{{ route('catalog.index') }}">
            <div class="filter-group"><strong>جست‌وجو</strong><input type="text" name="q" value="{{ request('q') }}" placeholder="نام، کد یا جنس..."></div>
            <div class="filter-group"><strong>دسته‌بندی</strong><label><input type="radio" name="category" value="" @checked(!request('category'))> همه محصولات</label>@foreach($categories as $category)<label><input type="radio" name="category" value="{{ $category->slug }}" @checked(request('category') === $category->slug)> {{ $category->name }}</label>@endforeach</div>
            <div class="filter-group"><strong>محدوده قیمت</strong><input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="از قیمت"><input style="margin-top:8px" type="number" name="max_price" value="{{ request('max_price') }}" placeholder="تا قیمت"></div>
            <div class="filter-group"><button class="btn btn-primary" style="width:100%" type="submit">اعمال فیلتر</button></div>
        </form>
    </aside>

    <div>
        <div class="catalog-toolbar"><span>{{ number_format($products->total()) }} محصول</span><form method="get">@foreach(request()->except('sort') as $key => $value)@if(!is_array($value))<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif @endforeach<select name="sort" onchange="this.form.submit()"><option value="newest" @selected(request('sort')==='newest')>جدیدترین</option><option value="price_asc" @selected(request('sort')==='price_asc')>ارزان‌ترین</option><option value="price_desc" @selected(request('sort')==='price_desc')>گران‌ترین</option><option value="oldest" @selected(request('sort')==='oldest')>قدیمی‌تر</option></select></form></div>
        <div class="catalog-products">
            @forelse($products as $product)
                <article class="product-card reveal">
                    <a class="product-media" href="{{ route('products.show', $product) }}">
                        <img src="{{ $product->primary_image }}" width="640" height="800" loading="lazy" alt="{{ $product->name }}">
                    </a>
                    <div class="product-meta"><div><small>{{ $product->category?->name }}</small><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3></div><strong>{{ number_format($product->final_price) }} <small>تومان</small></strong></div>
                </article>
            @empty
                <div style="grid-column:1/-1;padding:80px 0;text-align:center;color:#817b72">محصولی با این فیلتر پیدا نشد.</div>
            @endforelse
        </div>
        @if($products->hasPages())
            <nav class="pagination" aria-label="صفحه‌بندی">
                @if($products->onFirstPage())<span>‹</span>@else<a href="{{ $products->previousPageUrl() }}">‹</a>@endif
                @for($page=max(1,$products->currentPage()-2);$page<=min($products->lastPage(),$products->currentPage()+2);$page++)
                    @if($page===$products->currentPage())<span class="active">{{ $page }}</span>@else<a href="{{ $products->url($page) }}">{{ $page }}</a>@endif
                @endfor
                @if($products->hasMorePages())<a href="{{ $products->nextPageUrl() }}">›</a>@else<span>›</span>@endif
            </nav>
        @endif
    </div>
</section>
@endsection
