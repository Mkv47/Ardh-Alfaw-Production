@extends('admin.layout')
@section('page-title', 'الخدمات')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-cogs" style="color:var(--teal)"></i> الخدمات</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة خدمة</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>الأيقونة</th><th>العنوان</th><th>الوصف</th><th>الترتيب</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td><i class="{{ $item->icon }}" style="font-size:1.4rem;color:var(--teal)"></i></td>
                <td><strong>{{ $item->title }}</strong></td>
                <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->description }}</td>
                <td>{{ $item->sort_order }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.services.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.services.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف هذه الخدمة؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:var(--gray)">لا توجد خدمات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
