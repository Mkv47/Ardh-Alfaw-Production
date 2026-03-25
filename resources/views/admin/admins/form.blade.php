@extends('admin.layout')
@section('page-title', $admin ? 'تعديل مدير' : 'إضافة مدير')

@section('content')

<div class="page-head">
    <h2>
        <i class="fas fa-user-shield" style="color:var(--teal)"></i>
        {{ $admin ? 'تعديل بيانات المدير' : 'إضافة مدير جديد' }}
    </h2>
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<div class="card" style="max-width:580px">
    <div class="card-header">
        <h2>
            <i class="fas fa-{{ $admin ? 'edit' : 'plus' }}"></i>
            {{ $admin ? 'تعديل البيانات' : 'بيانات المدير الجديد' }}
        </h2>
    </div>
    <div class="card-body">
        <form action="{{ $admin ? route('admin.admins.update', $admin) : route('admin.admins.store') }}"
              method="POST">
            @csrf
            @if($admin) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $admin?->name) }}" required placeholder="اسم المدير">
            </div>

            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $admin?->email) }}" required placeholder="example@domain.com">
            </div>

            <div class="form-group">
                <label class="form-label">
                    كلمة المرور
                    @if($admin)
                        <span style="font-size:.8rem;color:var(--gray);font-weight:400">(اتركها فارغة للإبقاء على الحالية)</span>
                    @endif
                </label>
                <input type="password" name="password" class="form-control"
                       {{ $admin ? '' : 'required' }} autocomplete="new-password"
                       placeholder="{{ $admin ? '••••••••' : 'كلمة مرور قوية' }}">
            </div>

            <div style="display:flex;gap:12px;margin-top:24px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    {{ $admin ? 'حفظ التعديلات' : 'إضافة المدير' }}
                </button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>

@endsection
