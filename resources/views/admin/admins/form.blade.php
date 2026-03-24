@extends('admin.layout')
@section('page-title', $admin ? 'تعديل مدير' : 'إضافة مدير')

@section('content')
<div class="page-header">
    <h2>{{ $admin ? 'تعديل بيانات المدير' : 'إضافة مدير جديد' }}</h2>
    <a href="{{ route('admin.admins.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<div class="card" style="max-width:560px">
    <form action="{{ $admin ? route('admin.admins.update', $admin) : route('admin.admins.store') }}"
          method="POST">
        @csrf
        @if($admin) @method('PUT') @endif

        <div class="form-group">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $admin?->name) }}" required>
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $admin?->email) }}" required>
        </div>

        <div class="form-group">
            <label>كلمة المرور {{ $admin ? '(اتركها فارغة للإبقاء على الحالية)' : '' }}</label>
            <input type="password" name="password" class="form-control"
                   {{ $admin ? '' : 'required' }} autocomplete="new-password">
        </div>

        <div style="display:flex;gap:12px;margin-top:8px">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> {{ $admin ? 'حفظ التعديلات' : 'إضافة المدير' }}
            </button>
            <a href="{{ route('admin.admins.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
