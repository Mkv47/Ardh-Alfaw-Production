@extends('admin.layout')
@section('page-title', $item->exists ? 'تعديل مشروع' : 'إضافة مشروع')
@section('content')
<div class="page-head">
    <h2>{{ $item->exists ? 'تعديل مشروع' : 'إضافة مشروع جديد' }}</h2>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-back"><i class="fas fa-arrow-right"></i> رجوع</a>
</div>
<div class="card"><div class="card-body">
<form action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="form-grid">
        <div class="form-group span-2">
            <label>عنوان المشروع *</label>
            <input type="text" name="title" value="{{ old('title', $item->title) }}" required>
        </div>
        <div class="form-group span-2">
            <label>صورة المشروع (PNG / JPG / WebP — حد أقصى 5MB)</label>
            @include('admin.partials.image-cropper', ['currentImage' => $item->image ?? null])
        </div>
        <div class="form-group">
            <label>الأيقونة (احتياطي إن لم تُرفع صورة) *</label>
            <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="fas fa-anchor" required>
        </div>
        <div class="form-group">
            <label>مفتاح التصنيف *</label>
            <select name="category_key">
                @foreach(['marine'=>'بحري','transport'=>'نقل','supply'=>'توريد','contracts'=>'مقاولات'] as $key=>$label)
                <option value="{{ $key }}" {{ old('category_key',$item->category_key)==$key?'selected':'' }}>{{ $label }} ({{ $key }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>اسم التصنيف (بالعربية) *</label>
            <input type="text" name="category_label" value="{{ old('category_label', $item->category_label) }}" required>
        </div>
        <div class="form-group">
            <label>اسم العميل *</label>
            <input type="text" name="client" value="{{ old('client', $item->client) }}" required>
        </div>
        <div class="form-group">
            <label>سنة التنفيذ *</label>
            <input type="text" name="year" value="{{ old('year', $item->year) }}" maxlength="4" required>
        </div>
        <div class="form-group">
            <label>الترتيب</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
        </div>
        <div class="form-group span-2">
            <label>الوصف *</label>
            <textarea name="description" rows="3" required>{{ old('description', $item->description) }}</textarea>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-back">إلغاء</a>
    </div>
</form>
</div></div>
@endsection
