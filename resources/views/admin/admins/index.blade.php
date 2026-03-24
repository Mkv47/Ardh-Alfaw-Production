@extends('admin.layout')
@section('page-title', 'المديرون')

@section('content')
<div class="page-header">
    <h2>إدارة المديرين</h2>
    <a href="{{ route('admin.admins.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> إضافة مدير
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $admin->name }}
                    @if($admin->id === auth()->id())
                        <span style="font-size:.75rem;background:var(--teal);color:#fff;padding:2px 8px;border-radius:10px;margin-right:6px">أنت</span>
                    @endif
                </td>
                <td>{{ $admin->email }}</td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($admin->id !== auth()->id())
                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                              onsubmit="return confirm('هل تريد حذف هذا المدير؟')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:#888">لا يوجد مديرون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
