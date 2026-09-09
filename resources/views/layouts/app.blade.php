<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>@yield('title', 'خانه فرش | فرش، قالی، تابلو فرش و موکت')</title>
    <meta name="description" content="@yield('meta_description', 'فروشگاه تخصصی فرش، قالی، تابلو فرش، فرشینه و موکت با انتخاب حرفه‌ای، مشاوره دکوراسیون و خرید امن آنلاین.')">
    <meta name="theme-color" content="#ffffff">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'خانه فرش')">
    <meta property="og:description" content="@yield('meta_description', 'مجموعه‌ای انتخاب‌شده از فرش و کف‌پوش برای خانه‌های معاصر.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
    <link rel="preload" href="{{ asset('fonts/BYekan.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('head')
</head>
<body class="@yield('body_class')">
<a class="skip-link" href="#main">رفتن به محتوای اصلی</a>

<div class="announcement">ارسال تخصصی و بیمه‌شده به سراسر ایران <span>•</span> مشاوره رایگان انتخاب فرش</div>
<header class="site-header" data-header>
    <div class="container header-row">
        <a class="brand" href="{{ route('home') }}" aria-label="صفحه اصلی خانه فرش">
            <span class="brand-mark" aria-hidden="true">◈</span>
            <span><b>خانه فرش</b><small>CARPET HOUSE</small></span>
        </a>

        <nav class="desktop-nav" aria-label="منوی اصلی">
            <div class="nav-mega">
                <a href="{{ route('catalog.index') }}">فروشگاه <span>⌄</span></a>
                <div class="mega-panel">
                    <div class="mega-copy">
                        <small>CURATED COLLECTIONS</small>
                        <strong>برای هر فضا، یک بافت ماندگار</strong>
                        <p>از قالی دستباف تا موکت مدرن؛ بر اساس فضا، سبک و بودجه انتخاب کنید.</p>
                        <a class="text-link" href="{{ route('catalog.index') }}">مشاهده همه محصولات ←</a>
                    </div>
                    <div class="mega-links">
                        <a href="{{ route('catalog.index', ['category' => 'handmade']) }}"><span>قالی و فرش دستباف</span><small>اصیل و کلکسیونی</small></a>
                        <a href="{{ route('catalog.index', ['category' => 'machine-made']) }}"><span>فرش ماشینی</span><small>مدرن و کاربردی</small></a>
                        <a href="{{ route('catalog.index', ['category' => 'wall-rug']) }}"><span>تابلو فرش</span><small>هنر برای دیوار</small></a>
                        <a href="{{ route('catalog.index', ['category' => 'moquette']) }}"><span>موکت و موکت‌فرش</span><small>پروژه‌ای و مسکونی</small></a>
                        <a href="{{ route('catalog.index', ['category' => 'rug']) }}"><span>فرشینه و قالیچه</span><small>سبک و منعطف</small></a>
                        <a href="#projects"><span>پروژه‌های اجراشده</span><small>خانه، هتل و فضای تجاری</small></a>
                    </div>
                    <div class="mega-feature">
                        <div class="mini-rug" aria-hidden="true"></div>
                        <small>انتخاب ویژه این هفته</small>
                        <strong>کالکشن روشن مینیمال</strong>
                    </div>
                </div>
            </div>
            <a href="#collections">کالکشن‌ها</a>
            <a href="#projects">پروژه‌ها</a>
            <a href="#story">درباره ما</a>
            <a href="#consultation">مشاوره انتخاب</a>
        </nav>

        <div class="header-actions">
            <a class="icon-btn" href="{{ route('catalog.index') }}" aria-label="جست‌وجو">⌕</a>
            <a class="icon-btn cart-button" href="{{ route('cart.index') }}" aria-label="سبد خرید">♡<span>{{ collect(session('cart', []))->sum('quantity') }}</span></a>
            <button class="menu-toggle" type="button" data-menu-toggle aria-label="باز کردن منو" aria-expanded="false">☰</button>
        </div>
    </div>
</header>

@if(session('success'))
    <div class="toast" role="status">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="toast toast-error" role="alert">{{ $errors->first() }}</div>
@endif

<main id="main">@yield('content')</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark">◈</span><span><b>خانه فرش</b><small>CARPET HOUSE</small></span></a>
            <p>فروش و اجرای تخصصی فرش، قالی، تابلو فرش، فرشینه و موکت برای خانه‌ها و پروژه‌های حرفه‌ای.</p>
        </div>
        <div><strong>خرید</strong><a href="{{ route('catalog.index') }}">همه محصولات</a><a href="#collections">کالکشن‌ها</a><a href="#consultation">مشاوره</a></div>
        <div><strong>خدمات</strong><a href="#projects">اجرای پروژه</a><a href="#story">درباره مجموعه</a><a href="#">راهنمای نگهداری</a></div>
        <div><strong>ارتباط</strong><a href="tel:{{ config('store.phone') }}">{{ config('store.phone') }}</a><span>شنبه تا پنجشنبه، ۱۰ تا ۲۰</span><span>تهران، ایران</span></div>
    </div>
    <div class="container footer-bottom"><span>© {{ now()->year }} خانه فرش</span><span>پرداخت امن زرین‌پال • اطلاع‌رسانی کاوه‌نگار</span></div>
</footer>

<script src="{{ asset('assets/js/app.js') }}" defer></script>
@stack('scripts')
@stack('structured-data')
</body>
</html>
