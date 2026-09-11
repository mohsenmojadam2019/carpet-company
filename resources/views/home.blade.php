@extends('layouts.app')

@section('title', 'خانه فرش | تجربه تعاملی انتخاب فرش برای معماری امروز')
@section('meta_description', 'انتخاب و خرید قالی، فرش ماشینی، تابلوفرش، موکت و گلیم با استودیوی تعاملی فرش، مشاوره دکوراسیون، پروژه‌های اجراشده و پرداخت امن.')
@section('body_class', 'luxury-home')

@php
    $studioItems = $featuredProducts->take(6)->map(function ($product) {
        return [
            'name' => $product->name,
            'texture' => str_starts_with($product->primary_image, 'http') ? $product->primary_image : url($product->primary_image),
            'url' => route('products.show', $product),
            'price' => number_format($product->final_price).' تومان',
        ];
    })->values();

    if ($studioItems->isEmpty()) {
        $studioItems = collect([
            ['name'=>'قالی آریانا روشن','texture'=>asset('images/rug-01.svg'),'url'=>route('catalog.index'),'price'=>'مشاهده قیمت'],
            ['name'=>'قالی شاه‌عباسی','texture'=>asset('images/rug-terracotta.svg'),'url'=>route('catalog.index'),'price'=>'مشاهده قیمت'],
            ['name'=>'قالی نیلا سرمه‌ای','texture'=>asset('images/rug-navy.svg'),'url'=>route('catalog.index'),'price'=>'مشاهده قیمت'],
            ['name'=>'قالی مهتاب','texture'=>asset('images/rug-ivory.svg'),'url'=>route('catalog.index'),'price'=>'مشاهده قیمت'],
        ]);
    }
@endphp

@section('content')
<section class="lux-hero" aria-labelledby="home-title">
    <div class="lux-hero-stage" data-three-rug-studio tabindex="0" aria-label="استودیوی تعاملی انتخاب فرش">
        <img class="lux-room" src="{{ asset('assets/img/hero-room.svg') }}" width="1320" height="840" alt="فضای نشیمن روشن برای پیش‌نمایش فرش" fetchpriority="high">
        <div class="lux-room-glow" aria-hidden="true"></div>
        <img class="rug-studio-fallback" data-rug-fallback src="{{ $studioItems->first()['texture'] }}" width="900" height="600" alt="{{ $studioItems->first()['name'] }}">
        <canvas class="rug-studio-canvas" data-rug-canvas aria-hidden="true"></canvas>

        <div class="lux-side-note" aria-hidden="true">A MORE BEAUTIFUL TOMORROW</div>
        <div class="lux-slide-index"><b>01</b><span>/</span><small>{{ str_pad((string)$studioItems->count(), 2, '0', STR_PAD_LEFT) }}</small></div>
        <div class="rug-studio-hint"><span class="mouse-orbit">⌁</span><b>با حرکت موس، فرش را عوض کنید</b><small>Mouse / Touch / ← →</small></div>

        <div class="rug-studio-toolbar" role="group" aria-label="انتخاب طرح فرش">
            <button type="button" class="rug-studio-arrow" data-rug-prev aria-label="طرح قبلی">‹</button>
            <div class="rug-studio-thumbs">
                @foreach($studioItems as $index => $item)
                    <button type="button" class="rug-thumb {{ $index === 0 ? 'is-active' : '' }}" data-rug-index="{{ $index }}" aria-label="نمایش {{ $item['name'] }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                        <img src="{{ $item['texture'] }}" width="72" height="72" alt="" loading="{{ $index < 2 ? 'eager' : 'lazy' }}">
                    </button>
                @endforeach
            </div>
            <button type="button" class="rug-studio-arrow" data-rug-next aria-label="طرح بعدی">›</button>
        </div>
        <div class="rug-studio-live" aria-live="polite"><b data-rug-name>{{ $studioItems->first()['name'] }}</b><span data-rug-price>{{ $studioItems->first()['price'] }}</span></div>
    </div>

    <div class="lux-hero-copy">
        <span class="lux-kicker">PERSIAN HERITAGE · MODERN LIVING</span>
        <h1 id="home-title">خانه‌ای درخور<br><em>شکوه زندگی</em></h1>
        <p>فرش را فقط در یک عکس نبینید. طرح را روی فضا تجربه کنید، با موس بین بافت‌ها حرکت کنید و انتخابتان را پیش از خرید مقایسه کنید.</p>
        <div class="lux-hero-actions">
            <a class="lux-btn lux-btn-gold" href="{{ route('catalog.index') }}">مشاهده کلکسیون <span>←</span></a>
            <a class="lux-text-action" href="#rug-experience">تجربه تعاملی فرش <span>↓</span></a>
        </div>
        <div class="lux-heritage-mark"><span>◈</span><small>PERSIAN HERITAGE<br>FOR MODERN LIVING</small></div>
    </div>
</section>

<section class="lux-trust" aria-label="مزایای فروشگاه">
    <div class="lux-trust-item"><span>◇</span><div><b>اصالت ایرانی</b><small>انتخاب تخصصی و کنترل کیفیت</small></div></div>
    <div class="lux-trust-item"><span>♢</span><div><b>ضمانت کیفیت</b><small>مشخصات شفاف و قابل پیگیری</small></div></div>
    <div class="lux-trust-item"><span>▣</span><div><b>ارسال بیمه‌شده</b><small>بسته‌بندی مناسب فرش و قالی</small></div></div>
    <div class="lux-trust-item"><span>◌</span><div><b>مشاوره ویژه</b><small>انتخاب بر اساس نور و معماری</small></div></div>
    <div class="lux-trust-stats"><div><b>{{ number_format($featuredProducts->count() ?: 40) }}+</b><small>انتخاب شاخص</small></div><div><b>8</b><small>خانواده محصول</small></div><div><b>360°</b><small>تجربه تعاملی</small></div></div>
</section>

<section class="lux-mood" id="rug-experience">
    <div class="lux-mood-copy reveal">
        <span class="lux-kicker">ONE ROOM · MANY MOODS</span>
        <h2>یک فضا،<br>چند حال‌وهوای کاملاً متفاوت.</h2>
        <p>ماوس را روی فرش حرکت دهید؛ طرح‌ها بدون reload تغییر می‌کنند. در نسخه سه‌بعدی، خود سطح فرش هم نسبت به محل اشاره کمی موج و عمق می‌گیرد.</p>
        <a class="lux-link" href="{{ route('catalog.index') }}">مشاهده همه طرح‌ها ←</a>
    </div>
    <div class="lux-mood-art reveal">
        <div class="mood-rug-stack" data-rug-peel-v2>
            <div class="mood-under pattern-3"></div>
            <div class="mood-top pattern-1" data-rug-top></div>
            <div class="mood-fold" data-rug-fold aria-hidden="true"></div>
            <div class="mood-shadow" data-rug-shadow aria-hidden="true"></div>
            <button type="button" class="mood-handle" data-rug-handle aria-label="گرفتن و بلندکردن گوشه فرش">↙</button>
        </div>
        <div class="mood-caption"><span>↻</span><div><b>تعویض طرح در لحظه</b><small>همان فضا، با یک حس تازه</small></div></div>
    </div>
</section>

<section class="lux-section container" id="collections">
    <div class="lux-section-head reveal"><div><span class="lux-kicker">CURATED COLLECTIONS</span><h2>مجموعه‌هایی برای سبک زندگی شما</h2></div><a class="lux-link" href="{{ route('catalog.index') }}">مشاهده همه ←</a></div>
    <div class="lux-category-grid">
        @forelse($categories->take(4) as $index => $category)
            <a class="lux-category-card reveal" href="{{ route('catalog.index', ['category'=>$category->slug]) }}">
                <div class="lux-category-visual pattern-{{ ($index % 4) + 1 }}"></div>
                <div><small>0{{ $index + 1 }}</small><h3>{{ $category->name }}</h3><p>{{ $category->description ?: 'انتخابی دقیق برای خانه‌های امروزی.' }}</p><span>مشاهده مجموعه ←</span></div>
            </a>
        @empty
            @foreach([['قالی دستباف','میراثی از دست‌های هنرمند'],['فرش ماشینی','زیبایی مدرن برای زندگی امروز'],['تابلوفرش','هنر بر دیوار زمان'],['موکت','آرامش در گام‌های روزمره']] as $index => $item)
                <a class="lux-category-card reveal" href="{{ route('catalog.index') }}"><div class="lux-category-visual pattern-{{ $index + 1 }}"></div><div><small>0{{ $index + 1 }}</small><h3>{{ $item[0] }}</h3><p>{{ $item[1] }}</p><span>مشاهده مجموعه ←</span></div></a>
            @endforeach
        @endforelse
    </div>
</section>

<section class="lux-products lux-section container">
    <div class="lux-section-head reveal"><div><span class="lux-kicker">BESTSELLERS</span><h2>پرفروش‌ترین انتخاب‌ها</h2></div><a class="lux-link" href="{{ route('catalog.index') }}">ورود به فروشگاه ←</a></div>
    <div class="lux-product-grid">
        @forelse($featuredProducts->take(4) as $product)
            <article class="lux-product-card reveal">
                <a class="lux-product-media" href="{{ route('products.show',$product) }}"><img src="{{ $product->primary_image }}" width="520" height="620" loading="lazy" alt="{{ $product->name }}"></a>
                <div class="lux-product-info"><div><small>{{ $product->category?->name }}</small><h3><a href="{{ route('products.show',$product) }}">{{ $product->name }}</a></h3></div><strong>{{ number_format($product->final_price) }} <small>تومان</small></strong></div>
            </article>
        @empty
            @foreach([['طرح افشان شاهان','۲۸,۹۰۰,۰۰۰'],['طرح باغ بهشت','۳۱,۵۰۰,۰۰۰'],['طرح ماهور','۲۶,۹۰۰,۰۰۰'],['طرح شکارگاه','۳۹,۰۰۰,۰۰۰']] as $index => $item)
                <article class="lux-product-card reveal"><a class="lux-product-media" href="{{ route('catalog.index') }}"><span class="catalog-rug pattern-{{ $index + 1 }}"></span></a><div class="lux-product-info"><div><small>کالکشن منتخب</small><h3>{{ $item[0] }}</h3></div><strong>{{ $item[1] }} <small>تومان</small></strong></div></article>
            @endforeach
        @endforelse
    </div>
</section>

<section class="lux-designers">
    <div class="container lux-designers-grid">
        <div class="designer-copy reveal"><span class="lux-kicker">DESIGNER'S EDIT</span><h2>پیشنهاد طراحان برای سه فضای متفاوت</h2><p>به‌جای انتخاب تصادفی، فرش را بر اساس نور، متریال و فرم مبلمان ببینید.</p></div>
        <div class="designer-cards">
            <a class="designer-card reveal" href="{{ route('catalog.index',['sort'=>'newest']) }}"><img src="{{ asset('assets/img/hero-room.svg') }}" width="500" height="320" loading="lazy" alt="کلکسیون مدرن"><div><b>کلکسیون مدرن</b><small>برای خانه‌های امروزی</small></div></a>
            <a class="designer-card reveal" href="{{ route('catalog.index') }}"><img src="{{ asset('assets/img/atelier.svg') }}" width="500" height="320" loading="lazy" alt="کلکسیون کلاسیک"><div><b>کلکسیون کلاسیک</b><small>شکوهی آرام و ماندگار</small></div></a>
            <a class="designer-card reveal" href="{{ route('catalog.index') }}"><div class="designer-minimal pattern-2"></div><div><b>کلکسیون مینیمال</b><small>سادگی در اوج جزئیات</small></div></a>
        </div>
    </div>
</section>

<section class="lux-projects lux-section container" id="projects">
    <div class="lux-section-head reveal"><div><span class="lux-kicker">REAL SPACES</span><h2>پروژه‌های اجراشده</h2></div><a class="lux-link" href="{{ route('projects.index') }}">مشاهده پروژه‌ها ←</a></div>
    <div class="lux-project-grid">
        @forelse($projects->take(3) as $index => $project)
            <a class="lux-project-card reveal" href="{{ route('projects.show',$project) }}"><div class="lux-project-visual pattern-{{ ($index % 4) + 1 }}"></div><div><small>{{ $project->location }} · {{ $project->year }}</small><h3>{{ $project->title }}</h3><p>{{ $project->excerpt }}</p></div></a>
        @empty
            @foreach([['ویلای لواسان','تهران'],['پروژه مسکونی نیاوران','تهران'],['هتل بوتیک کاشان','کاشان']] as $index=>$item)<a class="lux-project-card reveal" href="{{ route('projects.index') }}"><div class="lux-project-visual pattern-{{ $index + 1 }}"></div><div><small>{{ $item[1] }}</small><h3>{{ $item[0] }}</h3><p>انتخاب فرش متناسب با نور، معماری و مسیر حرکت فضا.</p></div></a>@endforeach
        @endforelse
    </div>
</section>

<section class="lux-quote">
    <div class="container lux-quote-inner reveal"><span>“</span><blockquote>فرش خوب فقط فضا را پُر نمی‌کند؛<br>به معماری ریتم می‌دهد.</blockquote><small>{{ $settings['store_name'] ?? 'خانه فرش' }}</small></div>
</section>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('assets/css/home-luxury.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/rug-peel.css') }}">
@endpush

@push('scripts')
<script type="application/json" id="rug-studio-data">{!! json_encode($studioItems, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script type="module" src="{{ asset('assets/js/home-rug-studio.js') }}"></script>
<script src="{{ asset('assets/js/rug-peel.js') }}" defer></script>
@endpush

@push('structured-data')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Store','name'=>$settings['store_name'] ?? config('store.brand'),'url'=>url('/'),'telephone'=>$settings['store_phone'] ?? config('store.phone'),'priceRange'=>'$$$'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
