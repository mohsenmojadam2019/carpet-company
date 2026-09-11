@extends('layouts.app')
@section('title','خدمات | خانه فرش')
@section('meta_description','خدمات تخصصی مشاوره، انتخاب فرش، سفارش اختصاصی، اجرای پروژه، ارسال بیمه‌شده و پشتیبانی پس از خرید.')
@section('content')
<section class="page-hero"><div class="page-hero-inner"><div><div class="breadcrumbs"><a href="{{ route('home') }}">خانه</a> / خدمات</div><span class="page-kicker">SERVICES</span><h1>از انتخاب تا اجرا،<br>کنار پروژه شما.</h1><p>برای خرید شخصی یا پروژه‌های حرفه‌ای می‌توانید از مشاوره انتخاب، تطبیق با دکوراسیون، سفارش اختصاصی، برنامه‌ریزی ارسال و پشتیبانی استفاده کنید.</p></div><div class="page-visual"><img src="{{ asset('assets/img/hero-room.svg') }}" alt="خدمات انتخاب و اجرای فرش" loading="eager"></div></div></section>
<section class="page-section"><div class="page-container"><div class="service-list">@foreach([['◈','مشاوره انتخاب فرش','بررسی ابعاد، نور، مبلمان، رنگ و سبک فضا پیش از خرید.'],['◇','سفارش اختصاصی','ثبت درخواست برای ابعاد، طرح یا کاربرد خاص و دریافت پیشنهاد کارشناسی.'],['▣','پروژه‌های حرفه‌ای','انتخاب و اجرای فرش و کف‌پوش برای هتل، دفتر، لابی و فضای تجاری.'],['◎','ارسال و پشتیبانی','بسته‌بندی مناسب، ارسال بیمه‌شده و پیگیری سفارش تا تحویل.']] as $item)<article class="service-card"><span>{{ $item[0] }}</span><h3>{{ $item[1] }}</h3><p>{{ $item[2] }}</p></article>@endforeach</div></div></section>
@endsection
@push('head')<link rel="stylesheet" href="{{ asset('assets/css/corporate-pages.css') }}">@endpush
