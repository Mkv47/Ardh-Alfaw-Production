@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل شهادة' : 'إضافة شهادة')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل شهادة' : 'إضافة شهادة جديدة' }}</h2>
    <a href="{{ route('admin.certificates.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
@if($errors->any())
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 18px;margin-bottom:20px;color:#dc2626">
    <strong><i class="fas fa-exclamation-circle"></i> يرجى تصحيح الأخطاء التالية:</strong>
    <ul style="margin:8px 0 0 20px">
        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
</div>
@endif
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.certificates.update', $item) : route('admin.certificates.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="form-grid">

        <div class="form-group span-2">
            <label>عنوان الشهادة *</label>
            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
            @error('title') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group span-2">
            <label>ملف PDF {{ $item->exists ? '– اتركه فارغاً للإبقاء على الملف الحالي' : '*' }}</label>
            <input type="file" name="file" accept=".pdf" {{ $item->exists ? '' : 'required' }}>
            @error('file') <span class="form-error">{{ $message }}</span> @enderror
            @if($item->exists && $item->file)
                <div style="margin-top:8px">
                    <a href="{{ Storage::url($item->file) }}" target="_blank" class="btn btn-edit btn-sm">
                        <i class="fas fa-eye"></i> عرض PDF الحالي
                    </a>
                </div>
            @endif
        </div>

        <div class="form-group span-2">
            <label>صورة الغلاف (اختياري – PNG أو JPG تظهر على البطاقة)</label>
            <input type="file" name="thumbnail" accept=".png,.jpg,.jpeg">
            @error('thumbnail') <span class="form-error">{{ $message }}</span> @enderror
            @if($item->exists && $item->thumbnail)
                <div style="margin-top:10px;display:flex;align-items:center;gap:12px">
                    <img src="{{ Storage::url($item->thumbnail) }}" style="height:80px;border-radius:6px;border:1px solid #ddd">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;color:var(--danger);font-size:.85rem">
                        <input type="checkbox" name="remove_thumbnail" value="1"> حذف الغلاف الحالي
                    </label>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
