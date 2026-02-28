@extends('admin.layout')
@section('page-title', 'لوحة التحكم')

@section('content')

<div class="stats-grid">
    <a href="{{ route('admin.services.index') }}" class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-cogs"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['services'] }}</div>
            <div class="label">خدمة</div>
        </div>
    </a>
    <a href="{{ route('admin.projects.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-briefcase"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['projects'] }}</div>
            <div class="label">مشروع</div>
        </div>
    </a>
    <a href="{{ route('admin.news.index') }}" class="stat-card">
        <div class="stat-icon green"><i class="fas fa-newspaper"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['news'] }}</div>
            <div class="label">خبر</div>
        </div>
    </a>
    <a href="{{ route('admin.team.index') }}" class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['team'] }}</div>
            <div class="label">عضو فريق</div>
        </div>
    </a>
    <a href="{{ route('admin.tenders.index') }}" class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-file-contract"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['tenders'] }}</div>
            <div class="label">مناقصة</div>
        </div>
    </a>
    <a href="{{ route('admin.clients.index') }}" class="stat-card">
        <div class="stat-icon navy"><i class="fas fa-handshake"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['clients'] }}</div>
            <div class="label">عميل</div>
        </div>
    </a>
    <a href="{{ route('admin.gallery.index') }}" class="stat-card">
        <div class="stat-icon pink"><i class="fas fa-images"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['gallery'] }}</div>
            <div class="label">صورة</div>
        </div>
    </a>
    <a href="{{ route('admin.messages.index') }}" class="stat-card">
        <div class="stat-icon red"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <div class="num">{{ $counts['messages'] }}</div>
            <div class="label">رسالة @if($counts['unread'] > 0)<span style="color:var(--teal)">({{ $counts['unread'] }} جديدة)</span>@endif</div>
        </div>
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-bolt"></i> إجراءات سريعة</h2>
    </div>
    <div class="card-body quick-actions-wrap" style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('admin.settings') }}"       class="btn btn-primary"><i class="fas fa-sliders-h"></i> تعديل الإعدادات</a>
        <a href="{{ route('admin.services.create') }}" class="btn btn-edit"><i class="fas fa-plus"></i> إضافة خدمة</a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-edit"><i class="fas fa-plus"></i> إضافة مشروع</a>
        <a href="{{ route('admin.news.create') }}"     class="btn btn-edit"><i class="fas fa-plus"></i> إضافة خبر</a>
        <a href="{{ route('admin.tenders.create') }}"  class="btn btn-edit"><i class="fas fa-plus"></i> إضافة مناقصة</a>
        <a href="{{ route('admin.messages.index') }}"  class="btn btn-edit"><i class="fas fa-envelope"></i> عرض الرسائل</a>
    </div>
</div>

@endsection
