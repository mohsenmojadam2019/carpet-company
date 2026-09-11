@extends('layouts.app')

@section('title', 'خانه فرش | فرش را در فضای واقعی خانه ببینید')
@section('meta_description', 'انواع قالی، فرش ماشینی، تابلوفرش، موکت و گلیم را بر اساس دسته‌بندی انتخاب کنید و محصولات هر گروه را در فضای واقعی خانه مقایسه کنید.')
@section('body_class', 'room-showcase-home')

@php
    $showcaseData = $categories->map(function ($category) {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'products' => $category->products->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->primary_image,
                'price' => number_format($product->final_price).' تومان',
                'stock' => $product->stock,
                'url' => route('products.show', $product),
            ])->values(),
        ];
    })->filter(fn ($category) => count($category['products']) > 0)->values();

    if ($showcaseData->isEmpty() && $featuredProducts->isNotEmpty()) {
        $showcaseData = collect([[
            'id' => 0,
            'name' => 'منتخب‌ها',
            'slug' => 'featured',
            'products' => $featuredProducts->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->primary_image,
                'price' => number_format($product->final_price).' تومان',
                'stock' => $product->stock,
                'url' => route('products.show', $product),
            ])->values(),
        ]]);
    }

    $firstCategory = $showcaseData->first();
    $firstProduct = $firstCategory['products'][0] ?? null;
@endphp

@section('content')
<section class="room-hero" aria-labelledby="room-hero-title">
    <div class="room-hero-copy">
        <span class="room-kicker">LIVE ROOM PREVIEW</span>
        <h1 id="room-hero-title">فرش را انتخاب کن؛<br><em>همان لحظه در خانه ببین.</em></h1>
        <p>ابتدا دسته‌بندی را انتخاب کنید، سپس با فلش‌های عقب و جلو بین محصولات حرکت کنید. هر محصول فوراً روی کف همین فضا نمایش داده می‌شود.</p>
        <div class="room-hero-actions">
            <a class="room-primary-btn" href="{{ route('catalog.index') }}">ورود به فروشگاه ←</a>
            <a class="room-text-link" href="#room-selector">شروع مقایسه ↓</a>
        </div>
        <div class="room-benefits"><span>ضمانت اصالت</span><i></i><span>ارسال بیمه‌شده</span><i></i><span>مشاوره انتخاب</span></div>
    </div>

    <div class="room-visual" id="room-selector" data-room-selector tabindex="0" aria-label="پیش‌نمایش فرش در فضای خانه">
        <img class="room-background" src="{{ asset('assets/img/hero-room.svg') }}" width="1200" height="820" fetchpriority="high" alt="فضای نشیمن روشن برای مقایسه فرش">
        <div class="room-light" aria-hidden="true"></div>

        @if($firstProduct)
            <img class="room-floor-rug" data-room-rug src="{{ $firstProduct['image'] }}" width="900" height="560" alt="{{ $firstProduct['name'] }}">
            <a class="room-product-badge" data-room-product-link href="{{ $firstProduct['url'] }}">
                <small data-room-category>{{ $firstCategory['name'] }}</small>
                <b data-room-name>{{ $firstProduct['name'] }}</b>
                <span data-room-price>{{ $firstProduct['price'] }}</span>
            </a>
        @endif

        <div class="room-hint"><span>↔</span><b>با اسلایدر فرش را عوض کنید</b></div>
    </div>
</section>

<section class="selector-panel container" aria-label="انتخاب دسته‌بندی و محصول">
    <div class="selector-head">
        <div><span class="room-kicker">CHOOSE A COLLECTION</span><h2>اول دسته‌بندی، بعد فرش</h2></div>
        <span class="selector-count"><b data-current-number>01</b> / <span data-total-number>{{ str_pad((string)count($firstCategory['products'] ?? []), 2, '0', STR_PAD_LEFT) }}</span></span>
    </div>

    <div class="category-tabs" role="tablist" aria-label="دسته‌بندی محصولات">
        @foreach($showcaseData as $categoryIndex => $category)
            <button type="button" class="category-tab {{ $categoryIndex === 0 ? 'is-active' : '' }}" data-category-index="{{ $categoryIndex }}" role="tab" aria-selected="{{ $categoryIndex === 0 ? 'true' : 'false' }}">
                <span>{{ str_pad((string)($categoryIndex + 1), 2, '0', STR_PAD_LEFT) }}</span>{{ $category['name'] }}
            </button>
        @endforeach
    </div>

    <div class="product-slider-shell">
        <button type="button" class="product-slider-arrow" data-slider-prev aria-label="محصول قبلی">‹</button>
        <div class="product-slider-viewport">
            <div class="product-slider-track" data-product-track></div>
        </div>
        <button type="button" class="product-slider-arrow" data-slider-next aria-label="محصول بعدی">›</button>
    </div>
    <div class="slider-swipe-note">در موبایل می‌توانید کارت‌ها را با انگشت بکشید.</div>
</section>

<section class="home-trust-band">
    <div class="container home-trust-grid">
        <div><span>◇</span><b>اصالت ایرانی</b><small>مشخصات شفاف هر محصول</small></div>
        <div><span>♢</span><b>کیفیت کنترل‌شده</b><small>انتخاب مناسب برای استفاده واقعی</small></div>
        <div><span>▣</span><b>ارسال امن</b><small>بسته‌بندی و ارسال بیمه‌شده</small></div>
        <div><span>◌</span><b>مشاوره دکوراسیون</b><small>پیشنهاد بر اساس رنگ و نور فضا</small></div>
    </div>
</section>

<section class="home-section container">
    <div class="home-section-head"><div><span class="room-kicker">CURATED CATEGORIES</span><h2>همه چیز برای کف و دیوار خانه</h2></div><a href="{{ route('catalog.index') }}">همه محصولات ←</a></div>
    <div class="home-category-grid">
        @foreach($categories->take(4) as $index => $category)
            <a class="home-category-card" href="{{ route('catalog.index',['category'=>$category->slug]) }}">
                <div class="home-category-art pattern-{{ ($index % 4) + 1 }}"></div>
                <div><small>0{{ $index + 1 }}</small><h3>{{ $category->name }}</h3><p>{{ $category->description ?: 'انتخاب‌های متنوع برای فضاهای متفاوت.' }}</p><span>مشاهده مجموعه ←</span></div>
            </a>
        @endforeach
    </div>
</section>

<section class="home-section container">
    <div class="home-section-head"><div><span class="room-kicker">SELECTED PIECES</span><h2>انتخاب‌های شاخص</h2></div><a href="{{ route('catalog.index') }}">ورود به فروشگاه ←</a></div>
    <div class="home-product-grid">
        @foreach($featuredProducts->take(4) as $product)
            <article class="home-product-card">
                <a class="home-product-image" href="{{ route('products.show',$product) }}"><img src="{{ $product->primary_image }}" width="520" height="620" loading="lazy" alt="{{ $product->name }}"></a>
                <div><small>{{ $product->category?->name }}</small><h3><a href="{{ route('products.show',$product) }}">{{ $product->name }}</a></h3><strong>{{ number_format($product->final_price) }} <small>تومان</small></strong></div>
            </article>
        @endforeach
    </div>
</section>

<section class="home-projects">
    <div class="container">
        <div class="home-section-head"><div><span class="room-kicker">REAL SPACES</span><h2>پروژه‌های اجراشده</h2></div><a href="{{ route('projects.index') }}">مشاهده همه پروژه‌ها ←</a></div>
        <div class="home-project-grid">
            @foreach($projects->take(3) as $index => $project)
                <a class="home-project-card" href="{{ route('projects.show',$project) }}"><div class="home-project-art pattern-{{ ($index % 4)+1 }}"></div><div><small>{{ $project->location }} · {{ $project->year }}</small><h3>{{ $project->title }}</h3><p>{{ $project->excerpt }}</p></div></a>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/room-showcase.css') }}">
@endpush

@push('scripts')
<script type="application/json" id="room-showcase-data">{!! json_encode($showcaseData, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script src="{{ asset('assets/js/room-showcase.js') }}" defer></script>
@endpush

@push('structured-data')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Store','name'=>$settings['store_name'] ?? config('store.brand'),'url'=>url('/'),'telephone'=>$settings['store_phone'] ?? config('store.phone'),'priceRange'=>'$$$'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
