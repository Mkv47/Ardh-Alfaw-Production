@extends('admin.layout')
@section('page-title', 'رسائل التواصل')
@section('content')
<div class="page-head">
    <h2><i class="fas fa-envelope" style="color:var(--teal)"></i> رسائل التواصل ({{ $items->count() }})</h2>
</div>
<div class="card"><div class="table-wrap"><table>
    <thead><tr><th>#</th><th>الاسم</th><th>البريد</th><th>الهاتف</th><th>الشركة</th><th>الموضوع</th><th>الرسالة</th><th>التاريخ</th><th>حذف</th></tr></thead>
    <tbody>
    @forelse($items as $item)
    <tr class="{{ !$item->is_read ? 'msg-unread' : '' }}">
        <td>{{ $item->id }}</td>
        <td><strong>{{ $item->name }}</strong></td>
        <td><a href="mailto:{{ $item->email }}" style="color:var(--teal)">{{ $item->email }}</a></td>
        <td>{{ $item->phone ?? '—' }}</td>
        <td>{{ $item->company ?? '—' }}</td>
        <td>{{ $item->subject }}</td>
        <td style="max-width:220px">
            <span title="{{ $item->message }}" style="cursor:help">
                {{ Str::limit($item->message, 60) }}
            </span>
        </td>
        <td style="white-space:nowrap">{{ $item->created_at->format('Y/m/d H:i') }}</td>
        <td>
            <form action="{{ route('admin.messages.destroy', $item) }}" method="POST" onsubmit="return confirm('حذف هذه الرسالة؟')">
                @csrf @method('DELETE')
                <button class="btn btn-delete btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="9" style="text-align:center;color:var(--gray);padding:30px">لا توجد رسائل بعد</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection
