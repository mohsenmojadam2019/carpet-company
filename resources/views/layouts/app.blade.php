<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title>@yield('title', $settings['seo_default_title'] ?? $settings['store_name'] ?? 'خانه فرش')</title>
    <meta name="description" content="@yield('meta_description', $settings['seo_default_description'] ?? 'فروشگاه تخصصی فرش، قالی، تابلو فرش، فرشینه و موکت با انتخاب حرفه‌ای و خرید امن آنلاین.')">
    <meta name="theme-color" content="#ffffff">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', $settings['store_name'] ?? 'خانه فرش')">
    <meta property="og:description" content="@yield('meta_description', $settings['seo_default_description'] ?? '')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
    <link rel="preload" href="{{ asset('fonts/BYekan.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('head')
</head>
<body class="@yield('body_class')">
<a class="skip-link" href="#main">رفتن به محتوای اصلی</a>
<div class="announcement">ارسال تخصصی و بیمه‌شده به سراسر ایران <span>•</span> مشاوره رایگان انتخاب فرش</div>
<header class="site-header" data-header><div class="container header-row">
    <a class="brand" href="{{ route('home') }}" aria-label="صفحه اصلی {{ $settings['store_name'] ?? 'خانه فرش' }}"><span class="brand-mark" aria-hidden="true">◈</span><span><b>{{ $settings['store_name'] ?? 'خانه فرش' }}</b><small>{{ $settings['store_tagline'] ?? 'CARPET HOUSE' }}</small></span></a>
    <nav class="desktop-nav" aria-label="منوی اصلی">
        @if($menuItems->isNotEmpty())
            @foreach($menuItems as $root)
                @if($root->children->isNotEmpty())
                    <div class="nav-mega"><a href="{{ $root->resolvedUrl() }}">{{ $root->label }} <span>⌄</span></a><div class="mega-panel"><div class="mega-copy"><small>CURATED COLLECTIONS</small><strong>{{ $settings['store_tagline'] ?? 'برای هر فضا، یک بافت ماندگار' }}</strong><p>انتخاب حرفه‌ای فرش و کف‌پوش بر اساس سبک، فضا و بودجه.</p><a class="text-link" href="{{ $root->resolvedUrl() }}">مشاهده همه ←</a></div><div class="mega-links">@foreach($root->children->sortBy([['column','asc'],['sort_order','asc']]) as $child)<a href="{{ $child->resolvedUrl() }}"><span>{{ $child->label }}</span><small>مشاهده مجموعه</small></a>@endforeach</div><div class="mega-feature"><div class="mini-rug" aria-hidden="true"></div><small>انتخاب ویژه</small><strong>کالکشن روشن و معاصر</strong></div></div></div>
                @else
                    <a href="{{ $root->resolvedUrl() }}">{{ $root->label }}</a>
                @endif
            @endforeach
        @else
            <a href="{{ route('catalog.index') }}">فروشگاه</a><a href="{{ route('projects.index') }}">پروژه‌ها</a>
        @endif
        <a href="{{ route('home') }}#story">درباره ما</a><a href="{{ route('home') }}#consultation">مشاوره انتخاب</a>
    </nav>
    <div class="header-actions"><a class="icon-btn" href="{{ route('catalog.index') }}" aria-label="جست‌وجو">⌕</a><a class="icon-btn cart-button" href="{{ route('cart.index') }}" aria-label="سبد خرید">♡<span>{{ collect(session('cart', []))->sum('quantity') }}</span></a><button class="menu-toggle" type="button" data-menu-toggle aria-label="باز کردن منو" aria-expanded="false">☰</button></div>
</div></header>
@if(session('success'))<div class="toast" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="toast toast-error" role="alert">{{ $errors->first() }}</div>@endif
<main id="main">@yield('content')</main>
<footer class="site-footer"><div class="container footer-grid"><div class="footer-brand"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">◈</span><span><b>{{ $settings['store_name'] ?? 'خانه فرش' }}</b><small>{{ $settings['store_tagline'] ?? 'CARPET HOUSE' }}</small></span></a><p>فروش و اجرای تخصصی فرش، قالی، تابلو فرش، فرشینه و موکت برای خانه‌ها و پروژه‌های حرفه‌ای.</p></div><div><strong>خرید</strong><a href="{{ route('catalog.index') }}">همه محصولات</a><a href="{{ route('projects.index') }}">پروژه‌ها</a><a href="{{ route('home') }}#consultation">مشاوره</a></div><div><strong>خدمات</strong><a href="{{ route('projects.index') }}">اجرای پروژه</a><a href="{{ route('home') }}#story">درباره مجموعه</a><a href="{{ route('sitemap') }}">نقشه سایت</a></div><div><strong>ارتباط</strong>@if(!empty($settings['store_phone']))<a class="ltr" href="tel:{{ $settings['store_phone'] }}">{{ $settings['store_phone'] }}</a>@endif @if(!empty($settings['store_address']))<span>{{ $settings['store_address'] }}</span>@endif @if(!empty($settings['instagram_url']))<a class="ltr" href="{{ $settings['instagram_url'] }}" rel="noopener" target="_blank">Instagram</a>@endif</div></div><div class="container footer-bottom"><span>© {{ now()->year }} {{ $settings['store_name'] ?? 'خانه فرش' }}</span><span>پرداخت امن زرین‌پال • اطلاع‌رسانی کاوه‌نگار</span></div></footer>
<script src="{{ asset('assets/js/app.js') }}" defer></script>@stack('scripts')@stack('structured-data')
</body></html>
