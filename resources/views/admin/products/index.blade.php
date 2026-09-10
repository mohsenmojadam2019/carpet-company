@extends('layouts.admin')
@section('title','محصولات | مدیریت')
@section('content')
<div class="admin-top"><div><h1>محصولات</h1><div class="muted">{{ $products->total() }} محصول</div></div>@can('products.manage')<a class="admin-btn" href="{{ route('admin.products.create') }}">+ محصول جدید</a>@endcan</div>
<div class="admin-card">
<form class="admin-grid admin-grid-3" method="get" style="margin-bottom:16px"><div class="admin-field"><label>جست‌وجو</label><input name="q" value="{{ request('q') }}" placeholder="نام یا SKU"></div><div class="admin-field"><label>دسته</label><select name="category"><option value="">همه</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected((string)request('category')===(string)$c->id)>{{ $c->name }}</option>@endforeach</select></div><div class="admin-field" style="align-self:end"><button class="admin-btn alt">اعمال فیلتر</button></div></form>
<div style="overflow:auto"><table class="admin-table"><thead><tr><th>محصول</th><th>دسته</th><th>قیمت</th><th>موجودی</th><th>وضعیت</th><th></th></tr></thead><tbody>
@forelse($products as $p)<tr><td><div style="display:flex;gap:10px;align-items:center"><img class="thumb" src="{{ $p->primary_image }}" alt=""><div><b>{{ $p->name }}</b><div class="muted ltr">{{ $p->sku }}</div></div></div></td><td>{{ $p->category?->name }}</td><td>{{ number_format($p->final_price) }} تومان</td><td>{{ $p->stock }}</td><td><span class="badge {{ $p->is_active?'ok':'' }}">{{ $p->is_active?'فعال':'غیرفعال' }}</span></td><td><div class="admin-actions"><a class="admin-btn alt" href="{{ route('admin.products.edit',$p) }}">ویرایش</a><a class="admin-btn alt" target="_blank" href="{{ route('products.show',$p) }}">مشاهده</a></div></td></tr>@empty<tr><td colspan="6">محصولی یافت نشد.</td></tr>@endforelse
</tbody></table></div><div style="margin-top:16px">{{ $products->links() }}</div></div>
@endsection
