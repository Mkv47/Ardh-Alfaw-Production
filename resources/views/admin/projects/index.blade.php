@extends('admin.layout')
@section('page-title', 'المشاريع')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-briefcase" style="color:var(--teal)"></i> المشاريع</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مشروع</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>الأيقونة</th><th>العنوان</th><th>التصنيف</th><th>العميل</th><th>السنة</th><th>إجراءات</th></tr></thead>
            <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td><i class="{{ $item->icon }}" style="font-size:1.4rem;color:var(--teal)"></i></td>
                <td><strong>{{ $item->title }}</strong></td>
                <td><span class="badge badge-cat">{{ $item->category_label }}</span></td>
                <td>{{ $item->client }}</td>
                <td>{{ $item->year }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.projects.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.projects.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف هذا المشروع؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--gray)">لا توجد مشاريع</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
