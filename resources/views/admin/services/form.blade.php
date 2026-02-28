@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل خدمة' : 'إضافة خدمة')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل خدمة' : 'إضافة خدمة جديدة' }}</h2>
    <a href="{{ route('admin.services.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ $item->exists ? route('admin.services.update', $item) : route('admin.services.store') }}" method="POST">
            @csrf
            @if($item->exists) @method('PUT') @endif
            <div class="form-grid">
                <div class="form-group span-2">
                    <label>عنوان الخدمة *</label>
                    <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
                </div>
                <div class="form-group">
                    <label>الأيقونة (Font Awesome class) *</label>
                    <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-ship" required>
                    <small style="color:var(--gray)">مثال: fas fa-ship | fas fa-truck | fas fa-bolt</small>
                </div>
                <div class="form-group">
                    <label>الترتيب</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                </div>
                <div class="form-group span-2">
                    <label>الوصف *</label>
                    <textarea name="description" rows="4" required>{{ old('description', $item->description) }}</textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-back">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
