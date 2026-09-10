@extends('layouts.admin')
@section('title','پروژه‌ها | مدیریت')
@section('content')
<div class="admin-top"><div><h1>پروژه‌های شرکت</h1><div class="muted">نمونه‌کارهای اجرایی</div></div>@can('projects.manage')<a class="admin-btn" href="{{ route('admin.projects.create') }}">+ پروژه جدید</a>@endcan</div>
<div class="admin-card"><table class="admin-table"><thead><tr><th>پروژه</th><th>مکان</th><th>سال</th><th>وضعیت</th><th></th></tr></thead><tbody>@foreach($projects as $p)<tr><td><div style="display:flex;gap:10px;align-items:center"><img class="thumb" src="{{ $p->cover_url }}" alt=""><b>{{ $p->title }}</b></div></td><td>{{ $p->location }}</td><td>{{ $p->year }}</td><td><span class="badge {{ $p->is_active?'ok':'' }}">{{ $p->is_active?'فعال':'غیرفعال' }}</span></td><td><a class="admin-btn alt" href="{{ route('admin.projects.edit',$p) }}">ویرایش</a></td></tr>@endforeach</tbody></table>{{ $projects->links() }}</div>
@endsection
