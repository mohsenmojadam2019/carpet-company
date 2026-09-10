@extends('layouts.admin')
@section('title',($rule->exists?'ویرایش قانون تخفیف':'قانون تخفیف جدید').' | مدیریت')
@section('content')
<div class="admin-top"><div><h1>{{ $rule->exists?'ویرایش قانون تخفیف':'قانون تخفیف جدید' }}</h1><div class="muted">Rule engine برای تخفیف خودکار</div></div><a class="admin-btn alt" href="{{ route('admin.discount-rules.index') }}">بازگشت</a></div>
<form class="admin-grid" method="post" action="{{ $rule->exists?route('admin.discount-rules.update',$rule):route('admin.discount-rules.store') }}">@csrf @if($rule->exists)@method('PUT')@endif
<div class="admin-card admin-grid admin-grid-2">
<div class="admin-field"><label>نام قانون</label><input name="name" required value="{{ old('name',$rule->name) }}"></div>
<div class="admin-field"><label>نوع</label><select name="type"><option value="percent" @selected(old('type',$rule->type)==='percent')>درصدی</option><option value="fixed" @selected(old('type',$rule->type)==='fixed')>مبلغ ثابت</option></select></div>
<div class="admin-field"><label>مقدار</label><input type="number" min="1" name="value" required value="{{ old('value',$rule->value) }}"></div>
<div class="admin-field"><label>اولویت</label><input type="number" name="priority" value="{{ old('priority',$rule->priority??0) }}"></div>
<div class="admin-field"><label>دامنه</label><select name="scope" id="scope"><option value="all" @selected(old('scope',$rule->scope)==='all')>کل فروشگاه</option><option value="product" @selected(old('scope',$rule->scope)==='product')>محصولات انتخابی</option><option value="category" @selected(old('scope',$rule->scope)==='category')>دسته‌های انتخابی</option></select></div>
<div class="admin-field"><label>موارد مشمول</label><select name="scope_value[]" multiple size="8">@foreach($products as $p)<option data-kind="product" value="{{ $p->id }}" @selected(in_array($p->id,(array)old('scope_value',$rule->scope_value??[])))>محصول: {{ $p->name }}</option>@endforeach @foreach($categories as $c)<option data-kind="category" value="{{ $c->id }}" @selected(in_array($c->id,(array)old('scope_value',$rule->scope_value??[])))>دسته: {{ $c->name }}</option>@endforeach</select><small class="muted">برای انتخاب چند مورد Ctrl/Cmd را نگه دارید.</small></div>
<div class="admin-field"><label>شروع اعتبار</label><input class="ltr" type="datetime-local" name="starts_at" value="{{ old('starts_at',$rule->starts_at?->format('Y-m-d\TH:i')) }}"></div>
<div class="admin-field"><label>پایان اعتبار</label><input class="ltr" type="datetime-local" name="ends_at" value="{{ old('ends_at',$rule->ends_at?->format('Y-m-d\TH:i')) }}"></div>
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$rule->exists?$rule->is_active:true))> فعال</label>
</div><div><button class="admin-btn gold">ذخیره قانون</button></div></form>
@push('scripts')<script>const s=document.getElementById('scope'),o=[...document.querySelectorAll('[data-kind]')];function f(){o.forEach(x=>x.hidden=s.value!=='all'&&x.dataset.kind!==s.value);if(s.value==='all')o.forEach(x=>x.selected=false)}s?.addEventListener('change',f);f();</script>@endpush
@endsection
