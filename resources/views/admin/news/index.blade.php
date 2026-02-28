@extends('admin.layout')
@section('page-title', 'الأخبار')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-newspaper" style="color:var(--teal)"></i> الأخبار</h2>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة خبر</a>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>الأيقونة</th><th>العنوان</th><th>الشارة</th><th>التصنيف</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td><i class="{{ $item->icon }}" style="font-size:1.3rem;color:var(--teal)"></i></td>
        <td><strong>{{ $item->title }}</strong></td>
        <td><span class="badge badge-cat">{{ $item->badge }}</span></td>
        <td>{{ $item->category }}</td>
        <td>{{ $item->published_at->format('Y/m/d') }}</td>
        <td><div class="actions">
            <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div></td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center;color:var(--gray)">لا توجد أخبار</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
