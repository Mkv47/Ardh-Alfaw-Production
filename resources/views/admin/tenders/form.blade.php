@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل مناقصة' : 'إضافة مناقصة')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل مناقصة' : 'إضافة مناقصة جديدة' }}</h2>
    <a href="{{ route('admin.tenders.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.tenders.update', $item) : route('admin.tenders.store') }}" method="POST">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="form-group span-2">
            <label>عنوان المناقصة *</label>
            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
        </div>
        <div class="form-group">
            <label>نوع المناقصة *</label>
            <input type="text" name="type" value="{{ old('type', $item->type) }}" placeholder="توريد معدات" required>
        </div>
        <div class="form-group">
            <label>الحالة *</label>
            <select name="status">
                <option value="open"   {{ old('status',$item->status)=='open'?'selected':'' }}>مفتوحة</option>
                <option value="closed" {{ old('status',$item->status)=='closed'?'selected':'' }}>منتهية</option>
            </select>
        </div>
        <div class="form-group">
            <label>تاريخ الإغلاق *</label>
            <input type="date" name="deadline" value="{{ old('deadline', $item->exists ? $item->deadline->format('Y-m-d') : '') }}" required>
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
        <a href="{{ route('admin.tenders.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
