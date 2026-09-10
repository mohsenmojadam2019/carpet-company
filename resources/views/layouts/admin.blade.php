<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title','مدیریت | خانه فرش')</title>
    <link rel="preload" href="{{ asset('fonts/BYekan.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        :root{--admin-bg:#f8f7f3;--admin-card:#fff;--admin-border:#e9e5dc;--admin-text:#171714;--admin-muted:#77736b;--admin-accent:#a9864c}
        .admin-body{background:var(--admin-bg);color:var(--admin-text)}
        .admin-shell{display:grid;grid-template-columns:250px minmax(0,1fr);min-height:100vh}
        .admin-sidebar{position:sticky;top:0;height:100vh;background:#fff;border-left:1px solid var(--admin-border);padding:24px 18px;overflow:auto}
        .admin-sidebar .brand{margin-bottom:24px}.admin-nav{display:grid;gap:5px}
        .admin-nav a{display:flex;justify-content:space-between;align-items:center;padding:10px 12px;border-radius:12px;color:#4d4a44;text-decoration:none;font-size:14px}
        .admin-nav a:hover,.admin-nav a.active{background:#f2efe7;color:#15130f}
        .admin-nav small{color:#a29c91}.admin-main{padding:32px;min-width:0}
        .admin-top{display:flex;justify-content:space-between;gap:16px;align-items:center;margin-bottom:24px}.admin-top h1{font-size:28px;margin:0}
        .admin-card{background:#fff;border:1px solid var(--admin-border);border-radius:20px;padding:20px;box-shadow:0 10px 35px rgba(55,45,25,.035)}
        .admin-grid{display:grid;gap:16px}.admin-grid-2{grid-template-columns:repeat(2,minmax(0,1fr))}.admin-grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}
        .admin-table{width:100%;border-collapse:collapse}.admin-table th,.admin-table td{padding:13px 10px;text-align:right;border-bottom:1px solid #efede7;font-size:14px;vertical-align:middle}
        .admin-table th{color:#77736b;font-weight:400}.admin-table tr:last-child td{border-bottom:0}
        .admin-field{display:grid;gap:7px}.admin-field label{font-size:13px;color:#6d685f}.admin-field input,.admin-field select,.admin-field textarea{width:100%;border:1px solid #ded9cf;border-radius:12px;padding:11px 12px;background:#fff;font:inherit;outline:none}
        .admin-field input:focus,.admin-field select:focus,.admin-field textarea:focus{border-color:#b89a67;box-shadow:0 0 0 3px rgba(184,154,103,.12)}
        .admin-actions{display:flex;gap:8px;flex-wrap:wrap}.admin-btn{border:0;border-radius:11px;padding:10px 15px;background:#191815;color:#fff;text-decoration:none;cursor:pointer;font:inherit}.admin-btn.alt{background:#f1eee7;color:#29251f}.admin-btn.danger{background:#fff0ee;color:#9d2d25}.admin-btn.gold{background:#a9864c}
        .badge{display:inline-flex;padding:5px 9px;border-radius:999px;background:#f2efe8;font-size:12px}.badge.ok{background:#edf7ef;color:#24733b}.badge.warn{background:#fff6e8;color:#946111}
        .admin-flash{padding:12px 15px;border-radius:12px;margin-bottom:18px;background:#edf7ef;color:#24733b}.admin-error{background:#fff0ee;color:#9d2d25}
        .media-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px}.media-card{border:1px solid #e8e3d9;border-radius:14px;overflow:hidden;background:#fff}.media-card img{display:block;width:100%;aspect-ratio:1/1;object-fit:cover}.media-card .meta{padding:9px;font-size:12px}
        .thumb{width:54px;height:54px;border-radius:10px;object-fit:cover;background:#f1eee8}.muted{color:var(--admin-muted)}.ltr{direction:ltr;text-align:left}
        @media(max-width:980px){.admin-shell{grid-template-columns:1fr}.admin-sidebar{position:relative;height:auto;border-left:0;border-bottom:1px solid var(--admin-border)}.admin-nav{grid-template-columns:repeat(3,minmax(0,1fr))}.admin-main{padding:20px}.admin-grid-2,.admin-grid-3{grid-template-columns:1fr}.media-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:620px){.admin-nav{grid-template-columns:repeat(2,minmax(0,1fr))}.media-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.admin-main{padding:14px}.admin-table{display:block;overflow:auto}}
    </style>
    @stack('head')
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">◈</span><span><b>مدیریت {{ config('app.name') }}</b><small>CONTROL PANEL</small></span></a>
        <nav class="admin-nav">
            <a class="{{ request()->routeIs('admin.dashboard')?'active':'' }}" href="{{ route('admin.dashboard') }}">نمای کلی</a>
            @can('products.view')<a class="{{ request()->routeIs('admin.products.*')?'active':'' }}" href="{{ route('admin.products.index') }}">محصولات</a>@endcan
            @can('categories.view')<a class="{{ request()->routeIs('admin.categories.*')?'active':'' }}" href="{{ route('admin.categories.index') }}">دسته‌بندی‌ها</a>@endcan
            @can('orders.view')<a class="{{ request()->routeIs('admin.orders.*')?'active':'' }}" href="{{ route('admin.orders.index') }}">سفارش‌ها</a>@endcan
            @can('projects.view')<a class="{{ request()->routeIs('admin.projects.*')?'active':'' }}" href="{{ route('admin.projects.index') }}">پروژه‌ها</a>@endcan
            @can('discounts.view')<a class="{{ request()->routeIs('admin.coupons.*')?'active':'' }}" href="{{ route('admin.coupons.index') }}">کد تخفیف</a><a class="{{ request()->routeIs('admin.discount-rules.*')?'active':'' }}" href="{{ route('admin.discount-rules.index') }}">قوانین تخفیف</a>@endcan
            @can('media.view')<a class="{{ request()->routeIs('admin.media.*')?'active':'' }}" href="{{ route('admin.media.index') }}">مدیا لایبرری</a>@endcan
            @can('menus.view')<a class="{{ request()->routeIs('admin.menus.*')?'active':'' }}" href="{{ route('admin.menus.index') }}">مگامنو</a>@endcan
            @can('reports.view')<a class="{{ request()->routeIs('admin.reports.*')?'active':'' }}" href="{{ route('admin.reports.index') }}">گزارش‌ها</a>@endcan
            @can('users.view')<a class="{{ request()->routeIs('admin.users.*')?'active':'' }}" href="{{ route('admin.users.index') }}">کاربران</a><a class="{{ request()->routeIs('admin.roles.*')?'active':'' }}" href="{{ route('admin.roles.index') }}">نقش و دسترسی</a>@endcan
            @can('settings.view')<a class="{{ request()->routeIs('admin.settings.*')?'active':'' }}" href="{{ route('admin.settings.edit') }}">تنظیمات</a>@endcan
            <a href="{{ route('home') }}" target="_blank">مشاهده سایت ↗</a>
        </nav>
        <form method="post" action="{{ route('admin.logout') }}" style="margin-top:24px">@csrf<button class="admin-btn alt" style="width:100%" type="submit">خروج</button></form>
    </aside>
    <main class="admin-main">
        @if(session('success'))<div class="admin-flash">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="admin-flash admin-error">{{ $errors->first() }}</div>@endif
        @yield('content')
    </main>
</div>
@stack('scripts')
</body></html>
