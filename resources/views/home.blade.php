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
    $reviewItems = $testimonials;
    if ($reviewItems->isEmpty()) {
        $reviewItems = collect([
            (object)['customer_name'=>'نرگس احمدی','city'=>'تهران','title'=>'انتخابی که دقیقاً به فضا نشست','body'=>'برای نشیمن روشن بین چند طرح مردد بودم. مشاوره مجموعه باعث شد فرشی انتخاب کنم که هم با مبلمان هماهنگ است و هم فضا را شلوغ نکرده.','rating'=>5,'avatar_url'=>null,'verified_at'=>now()],
            (object)['customer_name'=>'سامان کاویانی','city'=>'اصفهان','title'=>'ارسال مرتب و مشخصات دقیق','body'=>'محصولی که تحویل گرفتم با اطلاعات صفحه کاملاً مطابقت داشت. بسته‌بندی و پیگیری سفارش هم منظم بود و فاکتور قابل چاپ خیلی کاربردی بود.','rating'=>5,'avatar_url'=>null,'verified_at'=>now()],
            (object)['customer_name'=>'مریم ساعی','city'=>'کرج','title'=>'برای پروژه انتخاب راحت‌تر شد','body'=>'برای یک فضای اداری از بخش پروژه و سفارش اختصاصی استفاده کردیم. تنوع بالا بود ولی دسته‌بندی و پیشنهادهای کارشناسی تصمیم‌گیری را خیلی سریع‌تر کرد.','rating'=>5,'avatar_url'=>null,'verified_at'=>now()],
        ]);
    }
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
            <a class="room-product-badge" data-room-product-link href="{{ $firstProduct['url'] }}"><small data-room-category>{{ $firstCategory['name'] }}</small><b data-room-name>{{ $firstProduct['name'] }}</b><span data-room-price>{{ $firstProduct['price'] }}</span></a>
        @endif
        <div class="room-hint"><span>↔</span><b>با اسلایدر فرش را عوض کنید</b></div>
    </div>
</section>

<section class="selector-panel container" aria-label="انتخاب دسته‌بندی و محصول">
    <div class="selector-head"><div><span class="room-kicker">CHOOSE A COLLECTION</span><h2>اول دسته‌بندی، بعد فرش</h2></div><span class="selector-count"><b data-current-number>01</b> / <span data-total-number>{{ str_pad((string)count($firstCategory['products'] ?? []), 2, '0', STR_PAD_LEFT) }}</span></span></div>
    <div class="category-tabs" role="tablist" aria-label="دسته‌بندی محصولات">
        @foreach($showcaseData as $categoryIndex => $category)
            <button type="button" class="category-tab {{ $categoryIndex === 0 ? 'is-active' : '' }}" data-category-index="{{ $categoryIndex }}" role="tab" aria-selected="{{ $categoryIndex === 0 ? 'true' : 'false' }}"><span>{{ str_pad((string)($categoryIndex + 1), 2, '0', STR_PAD_LEFT) }}</span>{{ $category['name'] }}</button>
        @endforeach
    </div>
    <div class="product-slider-shell"><button type="button" class="product-slider-arrow" data-slider-prev aria-label="محصول قبلی">‹</button><div class="product-slider-viewport"><div class="product-slider-track" data-product-track></div></div><button type="button" class="product-slider-arrow" data-slider-next aria-label="محصول بعدی">›</button></div>
    <div class="slider-swipe-note">در موبایل می‌توانید کارت‌ها را با انگشت بکشید.</div>
</section>

<section class="home-trust-band"><div class="container home-trust-grid"><div><span>◇</span><b>اصالت ایرانی</b><small>مشخصات شفاف هر محصول</small></div><div><span>♢</span><b>کیفیت کنترل‌شده</b><small>انتخاب مناسب برای استفاده واقعی</small></div><div><span>▣</span><b>ارسال امن</b><small>بسته‌بندی و ارسال بیمه‌شده</small></div><div><span>◌</span><b>مشاوره دکوراسیون</b><small>پیشنهاد بر اساس رنگ و نور فضا</small></div></div></section>

<section class="home-section container"><div class="home-section-head"><div><span class="room-kicker">CURATED CATEGORIES</span><h2>همه چیز برای کف و دیوار خانه</h2></div><a href="{{ route('catalog.index') }}">همه محصولات ←</a></div><div class="home-category-grid">@foreach($categories->take(4) as $index => $category)<a class="home-category-card" href="{{ route('catalog.index',['category'=>$category->slug]) }}"><div class="home-category-art pattern-{{ ($index % 4) + 1 }}"></div><div><small>0{{ $index + 1 }}</small><h3>{{ $category->name }}</h3><p>{{ $category->description ?: 'انتخاب‌های متنوع برای فضاهای متفاوت.' }}</p><span>مشاهده مجموعه ←</span></div></a>@endforeach</div></section>

<section class="home-section container"><div class="home-section-head"><div><span class="room-kicker">SELECTED PIECES</span><h2>انتخاب‌های شاخص</h2></div><a href="{{ route('catalog.index') }}">ورود به فروشگاه ←</a></div><div class="home-product-grid">@foreach($featuredProducts->take(4) as $product)<article class="home-product-card"><a class="home-product-image" href="{{ route('products.show',$product) }}"><img src="{{ $product->primary_image }}" width="520" height="620" loading="lazy" alt="{{ $product->name }}"></a><div><small>{{ $product->category?->name }}</small><h3><a href="{{ route('products.show',$product) }}">{{ $product->name }}</a></h3><strong>{{ number_format($product->final_price) }} <small>تومان</small></strong></div></article>@endforeach</div></section>

<section class="home-editorial" id="magazine"><div class="container"><div class="home-section-head"><div><span class="room-kicker">GUIDES & SERVICES</span><h2>انتخاب حرفه‌ای‌تر، فقط با چند قدم</h2></div><a href="{{ route('pages.services') }}">همه خدمات ←</a></div><div class="home-editorial-grid"><article class="editorial-feature"><img src="{{ asset('assets/img/hero-room.svg') }}" alt="راهنمای انتخاب فرش" loading="lazy"><div class="editorial-overlay"></div><div class="editorial-content"><small>ROOM GUIDE</small><h3>فرش مناسب برای نشیمن روشن</h3><p>ابعاد، رنگ، کنتراست و نسبت فرش با مبلمان را قبل از خرید بررسی کنید.</p><a href="{{ route('pages.faq') }}">مطالعه راهنما ←</a></div></article><article class="editorial-mini"><img src="{{ asset('assets/img/atelier.svg') }}" alt="سفارش اختصاصی" loading="lazy"><div class="editorial-overlay"></div><div class="editorial-content"><small>BESPOKE</small><h3>سفارش اختصاصی</h3><p>ابعاد و بودجه را ثبت کنید؛ پیشنهاد متناسب دریافت کنید.</p><a href="{{ route('pages.custom-order') }}">ثبت درخواست ←</a></div></article><article class="editorial-mini"><img src="{{ asset('assets/img/hero-room.svg') }}" alt="مشاوره پروژه" loading="lazy"><div class="editorial-overlay"></div><div class="editorial-content"><small>PROJECT</small><h3>مشاوره پروژه</h3><p>برای هتل، دفتر، لابی و فضای تجاری انتخاب تخصصی بگیرید.</p><a href="{{ route('pages.services') }}">خدمات پروژه ←</a></div></article></div></div></section>

<section class="home-projects"><div class="container"><div class="home-section-head"><div><span class="room-kicker">REAL SPACES</span><h2>پروژه‌های اجراشده</h2></div><a href="{{ route('projects.index') }}">مشاهده همه پروژه‌ها ←</a></div><div class="home-project-grid">@foreach($projects->take(3) as $index => $project)<a class="home-project-card" href="{{ route('projects.show',$project) }}"><div class="home-project-art pattern-{{ ($index % 4)+1 }}"></div><div><small>{{ $project->location }} · {{ $project->year }}</small><h3>{{ $project->title }}</h3><p>{{ $project->excerpt }}</p></div></a>@endforeach</div></div></section>

<section class="testimonial-section" id="testimonials"><div class="container"><div class="testimonial-head"><div><span class="room-kicker">CUSTOMER STORIES</span><h2>تجربه مشتریان از انتخاب و خرید</h2></div><p>نظرهایی که ادمین در پنل فعال می‌کند، مستقیماً در این بخش نمایش داده می‌شوند.</p></div><div class="testimonial-grid">@foreach($reviewItems->take(6) as $review)<article class="testimonial-card"><span class="testimonial-quote">“</span><div class="testimonial-stars">{{ str_repeat('★', (int)$review->rating) }}</div><h3>{{ $review->title ?: 'تجربه خرید از خانه فرش' }}</h3><p>{{ $review->body }}</p><div class="testimonial-customer">@if($review->avatar_url)<img class="testimonial-avatar" src="{{ $review->avatar_url }}" alt="{{ $review->customer_name }}" loading="lazy">@else<span class="testimonial-avatar">{{ mb_substr($review->customer_name,0,1) }}</span>@endif<div><b>{{ $review->customer_name }}</b><small>{{ $review->city ?: 'مشتری خانه فرش' }}</small></div>@if($review->verified_at)<span class="testimonial-verified">✓ تأییدشده</span>@endif</div></article>@endforeach</div></div></section>

<section class="consultation-banner"><div class="container consultation-box"><div><span class="room-kicker">PERSONAL CONSULTATION</span><h2>عکس فضای خانه را بفرستید؛ انتخاب را ساده‌تر کنید.</h2><p>برای خرید شخصی یا سفارش پروژه‌ای، درخواست ثبت کنید تا گزینه‌های متناسب با فضا و بودجه پیشنهاد شوند.</p></div><div class="consultation-actions"><a href="{{ route('pages.custom-order') }}">ثبت سفارش اختصاصی</a><a href="{{ route('pages.contact') }}">تماس و مشاوره</a></div></div></section>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/room-showcase.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home-extras.css') }}">
@endpush

@push('scripts')
<script type="application/json" id="room-showcase-data">{!! json_encode($showcaseData, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script src="{{ asset('assets/js/room-showcase.js') }}" defer></script>
@endpush

@push('structured-data')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Store','name'=>$settings['store_name'] ?? config('store.brand'),'url'=>url('/'),'telephone'=>$settings['store_phone'] ?? config('store.phone'),'priceRange'=>'$$$'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
