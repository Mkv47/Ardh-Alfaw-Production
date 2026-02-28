@extends('admin.layout')
@section('page-title', 'المناقصات')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-file-contract" style="color:var(--teal)"></i> المناقصات</h2>
    <a href="{{ route('admin.tenders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مناقصة</a>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>العنوان</th><th>النوع</th><th>الحالة</th><th>الموعد</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td><strong>{{ $item->title }}</strong></td>
        <td>{{ $item->type }}</td>
        <td><span class="badge {{ $item->status==='open' ? 'badge-open' : 'badge-closed' }}">{{ $item->status==='open'?'مفتوحة':'منتهية' }}</span></td>
        <td>{{ $item->deadline->format('Y/m/d') }}</td>
        <td><div class="actions">
            <a href="{{ route('admin.tenders.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.tenders.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div></td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:var(--gray)">لا توجد مناقصات</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
