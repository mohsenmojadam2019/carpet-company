@extends('layouts.admin')
@section('title',($coupon->exists?'ویرایش کد تخفیف':'کد تخفیف جدید').' | مدیریت')
@section('content')
<div class="admin-top"><div><h1>{{ $coupon->exists?'ویرایش کد تخفیف':'کد تخفیف جدید' }}</h1><div class="muted">کنترل مبلغ، سقف استفاده و بازه اعتبار</div></div><a class="admin-btn alt" href="{{ route('admin.coupons.index') }}">بازگشت</a></div>
<form class="admin-grid" method="post" action="{{ $coupon->exists?route('admin.coupons.update',$coupon):route('admin.coupons.store') }}">@csrf @if($coupon->exists)@method('PUT')@endif
<div class="admin-card admin-grid admin-grid-2">
<div class="admin-field"><label>کد</label><input class="ltr" name="code" required value="{{ old('code',$coupon->code) }}" placeholder="WELCOME10"></div>
<div class="admin-field"><label>نوع</label><select name="type" required><option value="percent" @selected(old('type',$coupon->type)==='percent')>درصدی</option><option value="fixed" @selected(old('type',$coupon->type)==='fixed')>مبلغ ثابت</option></select></div>
<div class="admin-field"><label>مقدار</label><input type="number" min="1" name="value" required value="{{ old('value',$coupon->value) }}"></div>
<div class="admin-field"><label>حداقل مبلغ سفارش</label><input type="number" min="0" name="min_order" value="{{ old('min_order',$coupon->min_order??0) }}"></div>
<div class="admin-field"><label>سقف تخفیف</label><input type="number" min="0" name="max_discount" value="{{ old('max_discount',$coupon->max_discount) }}"></div>
<div class="admin-field"><label>سقف دفعات استفاده</label><input type="number" min="1" name="usage_limit" value="{{ old('usage_limit',$coupon->usage_limit) }}"></div>
<div class="admin-field"><label>شروع اعتبار</label><input class="ltr" type="datetime-local" name="starts_at" value="{{ old('starts_at',$coupon->starts_at?->format('Y-m-d\TH:i')) }}"></div>
<div class="admin-field"><label>پایان اعتبار</label><input class="ltr" type="datetime-local" name="ends_at" value="{{ old('ends_at',$coupon->ends_at?->format('Y-m-d\TH:i')) }}"></div>
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$coupon->exists?$coupon->is_active:true))> فعال</label>
</div><div><button class="admin-btn gold">ذخیره کد تخفیف</button></div></form>
@endsection
