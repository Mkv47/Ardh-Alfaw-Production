@extends('admin.layout')
@section('page-title', 'فريق العمل')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-users" style="color:var(--teal)"></i> فريق العمل</h2>
    <a href="{{ route('admin.team.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة عضو</a>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>الأيقونة</th><th>الاسم</th><th>المنصب</th><th>واتساب</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td><i class="{{ $item->icon }}" style="font-size:1.3rem;color:var(--teal)"></i></td>
        <td><strong>{{ $item->name }}</strong></td>
        <td>{{ $item->role }}</td>
        <td>{{ $item->whatsapp ?? '—' }}</td>
        <td><div class="actions">
            <a href="{{ route('admin.team.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.team.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div></td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:var(--gray)">لا يوجد أعضاء</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
