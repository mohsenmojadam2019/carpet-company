<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>@yield('title','مدیریت | خانه فرش')</title><link rel="stylesheet" href="{{ asset('assets/css/app.css') }}"></head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">◈</span><span><b>مدیریت خانه فرش</b><small>CONTROL PANEL</small></span></a>
        <nav><a class="active" href="{{ route('admin.dashboard') }}">نمای کلی</a><a href="#orders">سفارش‌ها</a><a href="#products">محصولات</a><a href="#categories">دسته‌بندی‌ها</a><a href="#discounts">تخفیف و کد تخفیف</a><a href="#projects">پروژه‌ها</a><a href="#settings">تنظیمات فروشگاه</a></nav>
        <form method="post" action="{{ route('admin.logout') }}" style="position:absolute;bottom:28px;right:20px;left:20px">@csrf<button class="btn btn-ghost" style="width:100%" type="submit">خروج</button></form>
    </aside>
    <main class="admin-main">@yield('content')</main>
</div>
</body></html>
