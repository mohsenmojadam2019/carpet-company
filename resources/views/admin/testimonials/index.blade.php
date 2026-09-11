@extends('layouts.admin')
@section('title','نظرات مشتریان | مدیریت')
@section('toolbar_title','نظرات مشتریان')
@section('content')
<div class="admin-top"><div><span class="admin-page-kicker">CUSTOMER VOICE</span><h1>نظرات مشتریان</h1><div class="muted">نظرهای تأییدشده در صفحه اصلی نمایش داده می‌شوند.</div></div></div>
<div class="dashboard-grid">
<section class="admin-card">
    <div class="panel-head"><div><h2>افزودن نظر جدید</h2><p>برای نمایش در صفحه اصلی، گزینه فعال را روشن کنید.</p></div></div>
    <form class="admin-grid" method="post" enctype="multipart/form-data" action="{{ route('admin.testimonials.store') }}">@csrf
        <div class="admin-grid admin-grid-2">
            <div class="admin-field"><label>نام مشتری</label><input name="customer_name" required></div>
            <div class="admin-field"><label>شهر</label><input name="city"></div>
            <div class="admin-field"><label>عنوان کوتاه</label><input name="title" placeholder="مثلاً انتخاب دقیق برای نشیمن"></div>
            <div class="admin-field"><label>امتیاز</label><select name="rating">@for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} ستاره</option>@endfor</select></div>
            <div class="admin-field"><label>ترتیب نمایش</label><input type="number" min="0" name="sort_order" value="0"></div>
            <div class="admin-field"><label>عکس مشتری</label><input type="file" accept="image/*" name="avatar"></div>
        </div>
        <div class="admin-field"><label>متن نظر</label><textarea rows="6" name="body" required></textarea></div>
        <div class="admin-actions"><label class="badge"><input type="checkbox" name="is_active" value="1" checked> فعال</label><label class="badge"><input type="checkbox" name="is_featured" value="1"> ویژه</label><button class="admin-btn gold">افزودن نظر</button></div>
    </form>
</section>
<section class="admin-panel">
    <div class="panel-head"><div><h2>نظرهای موجود</h2><p>{{ number_format($testimonials->count()) }} نظر ثبت‌شده</p></div></div>
    <div class="admin-grid">
        @forelse($testimonials as $testimonial)
            <article class="admin-card" style="padding:16px">
                <form class="admin-grid" method="post" enctype="multipart/form-data" action="{{ route('admin.testimonials.update',$testimonial) }}">@csrf @method('PATCH')
                    <div style="display:flex;gap:12px;align-items:center">@if($testimonial->avatar_url)<img class="thumb" src="{{ $testimonial->avatar_url }}" alt="">@else<div class="thumb" style="display:grid;place-items:center">{{ mb_substr($testimonial->customer_name,0,1) }}</div>@endif<div><b>{{ $testimonial->customer_name }}</b><div class="muted">{{ $testimonial->city }} · {{ str_repeat('★',$testimonial->rating) }}</div></div></div>
                    <div class="admin-grid admin-grid-2"><div class="admin-field"><label>نام</label><input name="customer_name" value="{{ $testimonial->customer_name }}" required></div><div class="admin-field"><label>شهر</label><input name="city" value="{{ $testimonial->city }}"></div><div class="admin-field"><label>عنوان</label><input name="title" value="{{ $testimonial->title }}"></div><div class="admin-field"><label>امتیاز</label><select name="rating">@for($i=5;$i>=1;$i--)<option value="{{ $i }}" @selected($testimonial->rating===$i)>{{ $i }} ستاره</option>@endfor</select></div><div class="admin-field"><label>ترتیب</label><input type="number" min="0" name="sort_order" value="{{ $testimonial->sort_order }}"></div><div class="admin-field"><label>تعویض عکس</label><input type="file" accept="image/*" name="avatar"></div></div>
                    <div class="admin-field"><label>متن نظر</label><textarea rows="4" name="body" required>{{ $testimonial->body }}</textarea></div>
                    <div class="admin-actions"><label class="badge"><input type="checkbox" name="is_active" value="1" @checked($testimonial->is_active)> فعال</label><label class="badge"><input type="checkbox" name="is_featured" value="1" @checked($testimonial->is_featured)> ویژه</label><button class="admin-btn alt">ذخیره</button></div>
                </form>
                <form method="post" action="{{ route('admin.testimonials.destroy',$testimonial) }}" onsubmit="return confirm('این نظر حذف شود؟')">@csrf @method('DELETE')<button class="admin-btn danger" style="margin-top:10px">حذف</button></form>
            </article>
        @empty<div class="muted">هنوز نظری ثبت نشده است.</div>@endforelse
    </div>
</section>
</div>
@endsection
