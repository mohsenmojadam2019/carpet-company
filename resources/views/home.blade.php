@extends('layouts.app')

@section('title', 'خانه فرش | فروشگاه لوکس فرش، قالی، تابلو فرش و موکت')
@section('meta_description', 'انتخاب و خرید فرش، قالی، تابلو فرش، موکت و فرشینه با طراحی روشن و مینیمال، مشاوره دکوراسیون و پرداخت امن زرین‌پال.')

@section('content')
<section class="hero container">
    <div class="hero-copy reveal">
        <span class="eyebrow">PERSIAN TEXTURE · MODERN LIVING</span>
        <h1>فرش، فقط کفِ خانه نیست؛<br><em>هویتِ فضاست.</em></h1>
        <p>کالکشنی انتخاب‌شده از قالی‌های اصیل و کف‌پوش‌های معاصر برای خانه‌هایی که جزئیات در آن‌ها مهم است.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="{{ route('catalog.index') }}">مشاهده کالکشن</a>
            <a class="btn btn-ghost" href="#consultation">مشاوره انتخاب فرش</a>
        </div>
        <div class="hero-notes"><span><b>+۱۲</b> سبک و بافت</span><span><b>۳۶۰°</b> انتخاب برای فضا</span><span><b>امن</b> پرداخت زرین‌پال</span></div>
    </div>
    <div class="hero-visual reveal" data-parallax="0.08">
        <img src="{{ asset('assets/img/hero-room.svg') }}" width="900" height="980" alt="فضای نشیمن روشن با فرش ایرانی مدرن" fetchpriority="high">
        <div class="hero-card"><span>NEW COLLECTION</span><strong>Ivory Heritage</strong><small>بافت آرام برای فضای روشن</small></div>
    </div>
</section>

<section class="signature-strip" aria-label="مزایای فروشگاه">
    <div class="container signature-grid"><span>انتخاب تخصصی</span><i></i><span>ارسال بیمه‌شده</span><i></i><span>مشاوره دکوراسیون</span><i></i><span>ضمانت اصالت</span></div>
</section>

<section class="section container" id="collections">
    <div class="section-heading reveal"><div><span class="eyebrow">CURATED CATEGORIES</span><h2>از بافت اصیل تا فرم معاصر</h2></div><a class="text-link" href="{{ route('catalog.index') }}">همه دسته‌بندی‌ها ←</a></div>
    <div class="collection-grid">
        @forelse($categories as $index => $category)
            <a class="collection-card reveal collection-{{ ($index % 3) + 1 }}" href="{{ route('catalog.index', ['category' => $category->slug]) }}">
                <div class="collection-art"><span class="rug-pattern pattern-{{ ($index % 4) + 1 }}"></span></div>
                <div><small>0{{ $index + 1 }}</small><h3>{{ $category->name }}</h3><p>{{ $category->description ?: 'انتخاب‌های دقیق برای فضاهای متفاوت.' }}</p></div>
            </a>
        @empty
            @foreach([['قالی دستباف','اصالت در بافت و رنگ'],['فرش ماشینی','تعادل میان طراحی و کاربرد'],['تابلو فرش','یک قاب هنری برای دیوار'],['موکت و موکت‌فرش','راهکار یکپارچه برای پروژه'],['فرشینه و قالیچه','سبک، منعطف و امروزی']] as $index => $item)
                <a class="collection-card reveal collection-{{ ($index % 3) + 1 }}" href="{{ route('catalog.index') }}"><div class="collection-art"><span class="rug-pattern pattern-{{ ($index % 4) + 1 }}"></span></div><div><small>0{{ $index + 1 }}</small><h3>{{ $item[0] }}</h3><p>{{ $item[1] }}</p></div></a>
            @endforeach
        @endforelse
    </div>
</section>

<section class="experience section">
    <div class="container experience-grid">
        <div class="experience-copy reveal">
            <span class="eyebrow">SIGNATURE INTERACTION</span>
            <h2>گوشهٔ قالی را بگیر؛<br>طرح بعدی را کشف کن.</h2>
            <p>به‌جای اسلایدرهای تکراری، خودِ قالی تبدیل به رابط کاربری می‌شود. گوشه را بکشید؛ لبه با موج و عمق طبیعی بالا می‌آید و بافت زیرین آشکار می‌شود.</p>
            <a class="text-link" href="{{ route('catalog.index') }}">تجربه در صفحه محصول ←</a>
        </div>
        <div class="rug-peel-stage reveal" data-rug-peel-v2>
            <div class="rug-under pattern-3"><span>کالکشن دوم</span></div>
            <div class="rug-top pattern-1" data-rug-top><span>کالکشن اول</span></div>
            <div class="rug-edge-shadow" data-rug-shadow aria-hidden="true"></div>
            <div class="rug-fold" data-rug-fold aria-hidden="true"></div>
            <button type="button" class="rug-handle" data-rug-handle aria-label="کشیدن گوشه قالی"><b>↙</b><small>بکشید</small></button>
        </div>
    </div>
</section>

<section class="section container products-section">
    <div class="section-heading reveal"><div><span class="eyebrow">SELECTED PIECES</span><h2>انتخاب‌های شاخص این هفته</h2></div><a class="text-link" href="{{ route('catalog.index') }}">ورود به فروشگاه ←</a></div>
    <div class="product-grid">
        @forelse($featuredProducts as $product)
            <article class="product-card reveal">
                <a class="product-media" href="{{ route('products.show', $product) }}"><img src="{{ $product->primary_image }}" width="640" height="760" loading="lazy" alt="{{ $product->name }}"></a>
                <div class="product-meta"><div><small>{{ $product->category?->name }}</small><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3></div><strong>{{ number_format($product->final_price) }} <small>تومان</small></strong></div>
            </article>
        @empty
            @foreach([['فرش روشن آریانا','۴۹,۸۰۰,۰۰۰'],['قالیچه ماهور','۲۷,۴۰۰,۰۰۰'],['فرش هندسی سپیدار','۳۲,۹۰۰,۰۰۰'],['تابلو فرش باغ ایرانی','۱۸,۶۰۰,۰۰۰']] as $index => $item)
                <article class="product-card reveal"><a class="product-media" href="{{ route('catalog.index') }}"><span class="catalog-rug pattern-{{ $index + 1 }}"></span></a><div class="product-meta"><div><small>کالکشن روشن</small><h3>{{ $item[0] }}</h3></div><strong>{{ $item[1] }} <small>تومان</small></strong></div></article>
            @endforeach
        @endforelse
    </div>
</section>

<section class="editorial-section" id="story">
    <div class="container editorial-grid">
        <div class="editorial-photo reveal" data-parallax="0.05"><img src="{{ asset('assets/img/atelier.svg') }}" width="820" height="980" loading="lazy" alt="جزئیات بافت فرش در فضای روشن"></div>
        <div class="editorial-copy reveal"><span class="eyebrow">OUR POINT OF VIEW</span><h2>کمتر محصول؛<br>انتخاب دقیق‌تر.</h2><p>هدف، پر کردن صفحه با هزاران محصول نیست. هر بافت و رنگ بر اساس قابلیت هماهنگی با معماری و نور فضا انتخاب می‌شود تا تصمیم خرید ساده‌تر و مطمئن‌تر باشد.</p><div class="editorial-stats"><div><b>۱۵+</b><span>سال تجربه بازار</span></div><div><b>۱۲۰۰+</b><span>پروژه و سفارش</span></div><div><b>۴.۹/۵</b><span>رضایت مشتری</span></div></div></div>
    </div>
</section>

<section class="section container" id="projects">
    <div class="section-heading reveal"><div><span class="eyebrow">PROJECTS</span><h2>فرش در معماری واقعی</h2></div><span class="muted">خانه · هتل · دفتر · فضای تجاری</span></div>
    <div class="project-grid">
        @forelse($projects as $project)
            <article class="project-card reveal"><div class="project-art pattern-{{ ($loop->index % 4) + 1 }}"></div><div><small>{{ $project->location }} · {{ $project->year }}</small><h3>{{ $project->title }}</h3><p>{{ $project->excerpt }}</p></div></article>
        @empty
            @foreach([['ویلای روشن لواسان','پالت کرم و خاکی با قالی مرکزی'],['سوئیت بوتیک تهران','بافت مینیمال برای فضای کوچک'],['لابی اقامتگاه کویر','قالی ایرانی در معماری معاصر']] as $index => $item)
                <article class="project-card reveal"><div class="project-art pattern-{{ $index + 2 }}"></div><div><small>پروژه منتخب</small><h3>{{ $item[0] }}</h3><p>{{ $item[1] }}</p></div></article>
            @endforeach
        @endforelse
    </div>
</section>

<section class="consultation" id="consultation">
    <div class="container consultation-inner reveal"><div><span class="eyebrow">PERSONAL CONSULTATION</span><h2>عکس فضای خانه را بفرستید؛<br>ما فرش مناسب را پیشنهاد می‌کنیم.</h2></div><div><p>ابعاد، نور، رنگ مبلمان و سبک فضا بررسی می‌شود و چند انتخاب مشخص دریافت می‌کنید؛ بدون سردرگمی میان صدها محصول.</p><a class="btn btn-primary" href="{{ route('catalog.index') }}">شروع انتخاب</a></div></div>
</section>
@endsection

@push('head')<link rel="stylesheet" href="{{ asset('assets/css/rug-peel.css') }}">@endpush
@push('scripts')<script src="{{ asset('assets/js/rug-peel.js') }}" defer></script>@endpush
@push('structured-data')
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Store','name'=>config('store.brand'),'url'=>url('/'),'telephone'=>config('store.phone'),'priceRange'=>'$$$'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush
