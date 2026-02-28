@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل صورة' : 'إضافة صورة')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل عنصر المعرض' : 'إضافة عنصر جديد' }}</h2>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="form-group span-2">
            <label>التسمية / الوصف *</label>
            <input type="text" name="caption" value="{{ old('caption', $item->caption) }}" required>
        </div>
        <div class="form-group span-2">
            <label>الصورة (PNG / JPG / WebP — حد أقصى 5MB)</label>
            @include('admin.partials.image-cropper', ['currentImage' => $item->image ?? null])
        </div>
        <div class="form-group">
            <label>الأيقونة (احتياطي إن لم تُرفع صورة) *</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-ship" required>
        </div>
        <div class="form-group">
            <label>المفتاح الفريد (بالإنجليزية) *</label>
            <input type="text" name="key" value="{{ old('key', $item->key) }}" placeholder="marine1" required>
            <small style="color:var(--gray)">حروف وأرقام فقط بدون مسافات</small>
        </div>
        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
