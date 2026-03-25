@extends('admin.layout')
@section('page-title', 'المديرون')

@section('content')

<div class="page-head">
    <h2><i class="fas fa-user-shield" style="color:var(--teal)"></i> إدارة المديرين</h2>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة مدير
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-list"></i> قائمة المديرين</h2>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>إجراءات</th>
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
                        <div class="actions">
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-edit btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($admin->id !== auth()->id())
                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                                  onsubmit="return confirm('هل تريد حذف هذا المدير؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-delete btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:var(--gray)">لا يوجد مديرون بعد</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
