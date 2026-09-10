@extends('layouts.admin')
@section('title','دسته‌بندی‌ها | مدیریت')
@section('content')
<div class="admin-top"><div><h1>دسته‌بندی‌ها</h1><div class="muted">ساختار کاتالوگ و مگامنو</div></div>@can('categories.manage')<a class="admin-btn" href="{{ route('admin.categories.create') }}">+ دسته جدید</a>@endcan</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>نام</th><th>والد</th><th>محصول</th><th>ترتیب</th><th>وضعیت</th><th></th></tr></thead><tbody>@foreach($categories as $c)<tr><td><b>{{ $c->name }}</b><div class="muted ltr">{{ $c->slug }}</div></td><td>{{ $c->parent?->name ?: '—' }}</td><td>{{ $c->products_count }}</td><td>{{ $c->sort_order }}</td><td><span class="badge {{ $c->is_active?'ok':'' }}">{{ $c->is_active?'فعال':'غیرفعال' }}</span></td><td><a class="admin-btn alt" href="{{ route('admin.categories.edit',$c) }}">ویرایش</a></td></tr>@endforeach</tbody></table></div>
@endsection
