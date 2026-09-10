@extends('layouts.app')
@section('title','پروژه‌های اجراشده | خانه فرش')
@section('meta_description','نمونه پروژه‌های طراحی و اجرای فرش، قالی و موکت برای فضاهای مسکونی، هتل و پروژه‌های معماری.')
@section('content')
<section class="section"><div class="container"><div class="section-head"><div><span class="eyebrow">PROJECT ARCHIVE</span><h1>پروژه‌های اجراشده</h1></div><p>انتخاب بافت، رنگ و ابعاد متناسب با معماری هر فضا؛ از خانه‌های شخصی تا پروژه‌های تجاری.</p></div><div class="collection-grid">@forelse($projects as $project)<a class="collection-card" href="{{ route('projects.show',$project) }}"><img src="{{ $project->cover_url }}" alt="{{ $project->title }}" loading="lazy" width="700" height="520"><div class="collection-info"><span>{{ $project->location ?: 'پروژه اختصاصی' }} @if($project->year)• {{ $project->year }}@endif</span><h2>{{ $project->title }}</h2><p>{{ $project->excerpt }}</p></div></a>@empty<div class="empty-state">هنوز پروژه‌ای منتشر نشده است.</div>@endforelse</div><div style="margin-top:28px">{{ $projects->links() }}</div></div></section>
@endsection
