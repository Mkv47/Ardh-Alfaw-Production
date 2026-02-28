@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل خبر' : 'إضافة خبر')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل خبر' : 'إضافة خبر جديد' }}</h2>
    <a href="{{ route('admin.news.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="form-group span-2">
            <label>عنوان الخبر *</label>
            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
        </div>
        <div class="form-group span-2">
            <label>صورة الخبر (PNG / JPG / WebP — حد أقصى 5MB)</label>
            @if($item->image)
                <div style="margin-bottom:10px">
                    <img src="{{ Storage::url($item->image) }}" alt="صورة حالية" style="max-height:140px;border-radius:8px;border:2px solid #e2e8f0;">
                </div>
                <label style="font-weight:400;font-size:.9rem">
                    <input type="checkbox" name="remove_image" value="1"> حذف الصورة الحالية
                </label><br><br>
            @endif
            <input type="file" name="image" accept="image/*">
            <small style="color:var(--gray)">اتركه فارغاً للإبقاء على الأيقونة أو الصورة الحالية</small>
        </div>
        <div class="form-group">
            <label>الأيقونة (احتياطي إن لم تُرفع صورة) *</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-newspaper" required>
        </div>
        <div class="form-group">
            <label>الشارة (badge) *</label>
            <input type="text" name="badge" value="{{ old('badge', $item->badge) }}" placeholder="جديد" required>
        </div>
        <div class="form-group">
            <label>التصنيف *</label>
            <input type="text" name="category" value="{{ old('category', $item->category) }}" placeholder="خدمات بحرية" required>
        </div>
        <div class="form-group">
            <label>تاريخ النشر *</label>
            <input type="date" name="published_at" value="{{ old('published_at', $item->exists ? $item->published_at->format('Y-m-d') : '') }}" required>
        </div>
        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
        <div class="form-group span-2">
            <label>ملخص الخبر *</label>
            <textarea name="excerpt" rows="4" required>{{ old('excerpt', $item->excerpt) }}</textarea>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
