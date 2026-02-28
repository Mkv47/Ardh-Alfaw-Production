@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل عضو' : 'إضافة عضو')
@section('content')

<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل عضو الفريق' : 'إضافة عضو جديد' }}</h2>
    <a href="{{ route('admin.team.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>

<div class="card"><div class="card-body">
<form id="teamForm" action="{{ $item->exists ? route('admin.team.update', $item) : route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="form-grid">
        <div class="form-group">
            <label>الاسم *</label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
        </div>
        <div class="form-group">
            <label>المنصب / الدور *</label>
            <input type="text" name="role" value="{{ old('role', $item->role) }}" required>
        </div>

        <div class="form-group span-2">
            <label>الصورة الشخصية (PNG / JPG / WebP — حد أقصى 5MB)</label>
            @include('admin.partials.image-cropper', ['currentImage' => $item->image ?? null])
        </div>

        <div class="form-group">
            <label>الأيقونة (احتياطي إن لم تُرفع صورة) *</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-user-tie" required>
        </div>
        <div class="form-group">
            <label>رقم واتساب (اختياري)</label>
            <input type="text" name="whatsapp" value="{{ old('whatsapp', $item->whatsapp) }}" placeholder="9647845000007">
        </div>
        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
        <div class="form-group span-2">
            <label>نبذة مختصرة *</label>
            <textarea name="bio" rows="3" required>{{ old('bio', $item->bio) }}</textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.team.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>

@endsection
