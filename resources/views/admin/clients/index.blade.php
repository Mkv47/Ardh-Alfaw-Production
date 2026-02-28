@extends('admin.layout')
@section('page-title', 'العملاء')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-handshake" style="color:var(--teal)"></i> العملاء والشركاء</h2>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة عميل</a>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>الأيقونة</th><th>الاسم</th><th>الترتيب</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td><i class="{{ $item->icon }}" style="font-size:1.3rem;color:var(--teal)"></i></td>
        <td><strong>{{ $item->name }}</strong></td>
        <td>{{ $item->sort_order }}</td>
        <td><div class="actions">
            <a href="{{ route('admin.clients.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.clients.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div></td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center;color:var(--gray)">لا يوجد عملاء</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
