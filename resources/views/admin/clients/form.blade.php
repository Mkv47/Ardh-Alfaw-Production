@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل عميل' : 'إضافة عميل')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل عميل' : 'إضافة عميل جديد' }}</h2>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.clients.update', $item) : route('admin.clients.store') }}" method="POST">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="form-group span-2">
            <label>اسم العميل / المؤسسة *</label>
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
        </div>
        <div class="form-group">
            <label>الأيقونة *</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-building" required>
        </div>
        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
