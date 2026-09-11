@extends('layouts.app')
@section('title','درباره ما | خانه فرش')
@section('meta_description','درباره مجموعه خانه فرش، رویکرد انتخاب محصولات، پروژه‌های اجراشده و خدمات تخصصی فرش و کف‌پوش.')
@section('content')
<section class="page-hero"><div class="page-hero-inner"><div><div class="breadcrumbs"><a href="{{ route('home') }}">خانه</a> / درباره ما</div><span class="page-kicker">ABOUT CARPET HOUSE</span><h1>انتخاب فرش،<br>بخشی از طراحی فضاست.</h1><p>خانه فرش با تمرکز بر کیفیت بافت، اصالت، تناسب رنگ و کاربرد واقعی، مجموعه‌ای از قالی دستباف، فرش ماشینی، موکت، گلیم، رانر و آثار دکوراتیو را برای خانه و پروژه‌های حرفه‌ای گردآوری می‌کند.</p></div><div class="page-visual"><img src="{{ asset('assets/img/atelier.svg') }}" alt="جزئیات بافت فرش" loading="eager"></div></div></section>
<section class="page-section"><div class="page-container"><div class="page-grid"><article class="info-card"><span class="page-kicker">01</span><h3>انتخاب تخصصی</h3><p>هر محصول بر اساس متریال، دوام، سبک و قابلیت هماهنگی با معماری بررسی می‌شود.</p></article><article class="info-card"><span class="page-kicker">02</span><h3>شفافیت در خرید</h3><p>مشخصات، ابعاد، قیمت، وضعیت موجودی و اطلاعات پرداخت به‌صورت روشن ارائه می‌شود.</p></article><article class="info-card"><span class="page-kicker">03</span><h3>پروژه و مشاوره</h3><p>برای خانه، هتل، دفتر و فضای تجاری می‌توانید از انتخاب تا اجرا از مشاوره تخصصی استفاده کنید.</p></article></div></div></section>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/corporate-pages.css') }}">@endpush
