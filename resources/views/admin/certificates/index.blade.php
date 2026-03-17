@extends('admin.layout')
@section('page-title', 'الشهادات')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-certificate" style="color:var(--teal)"></i> الشهادات والاعتمادات</h2>
    <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة شهادة</a>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>معاينة</th><th>العنوان</th><th>الترتيب</th><th>إجراءات</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr>
        <td>{{ $item->id }}</td>
        <td>
            @if($item->thumbnail)
                <img src="{{ Storage::url($item->thumbnail) }}" style="height:48px;width:72px;object-fit:cover;border-radius:4px;border:1px solid #eee">
            @else
                <span style="display:inline-flex;align-items:center;justify-content:center;width:72px;height:48px;background:#fef2f2;border-radius:4px">
                    <i class="fas fa-file-pdf" style="color:#dc2626;font-size:1.5rem"></i>
                </span>
            @endif
        </td>
        <td><strong>{{ $item->title }}</strong></td>
        <td>{{ $item->sort_order }}</td>
        <td><div class="actions">
            <a href="{{ Storage::url($item->file) }}" target="_blank" class="btn btn-sm" style="background:#e0f2fe;color:#0369a1" title="عرض PDF">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('admin.certificates.edit', $item) }}" class="btn btn-edit btn-sm"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.certificates.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف الشهادة؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </div></td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center;color:var(--gray)">لا توجد شهادات بعد</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
