@extends('admin.layout')
@section('page-title', 'النسخ الاحتياطي')

@section('content')
<div class="page-header">
    <h2>النسخ الاحتياطي والاستيراد</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:860px">

    {{-- Export --}}
    <div class="card" style="padding:28px">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#1976d2,#00acc1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-download" style="color:#fff;font-size:1.2rem"></i>
            </div>
            <div>
                <h3 style="margin:0;font-size:1.05rem;color:var(--navy)">تصدير النسخة الاحتياطية</h3>
                <p style="margin:4px 0 0;font-size:.85rem;color:#666">تحميل كل البيانات والصور</p>
            </div>
        </div>

        <p style="font-size:.88rem;color:#555;line-height:1.7;margin-bottom:20px">
            يشمل الملف المُصدَّر:
        </p>
        <ul style="font-size:.85rem;color:#555;line-height:2;padding-right:18px;margin-bottom:24px">
            <li>جميع بيانات قاعدة البيانات (الخدمات، المشاريع، الأخبار، الفريق، المناقصات، العملاء، المعرض، الشهادات، الإعدادات، الرسائل، المديرون)</li>
            <li>جميع الصور والملفات المرفوعة</li>
        </ul>

        <a href="{{ route('admin.backup.export') }}"
           class="btn-primary"
           style="display:inline-flex;align-items:center;gap:8px;text-decoration:none">
            <i class="fas fa-file-archive"></i>
            تحميل النسخة الاحتياطية (.tar.gz)
        </a>
    </div>

    {{-- Import --}}
    <div class="card" style="padding:28px">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#e53935,#b71c1c);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-upload" style="color:#fff;font-size:1.2rem"></i>
            </div>
            <div>
                <h3 style="margin:0;font-size:1.05rem;color:var(--navy)">استيراد نسخة احتياطية</h3>
                <p style="margin:4px 0 0;font-size:.85rem;color:#666">استعادة البيانات من ملف ZIP</p>
            </div>
        </div>

        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:12px 14px;margin-bottom:20px;font-size:.84rem;color:#5d4037;line-height:1.7">
            <i class="fas fa-exclamation-triangle" style="color:#f59e0b;margin-left:6px"></i>
            <strong>تحذير:</strong> سيؤدي الاستيراد إلى <strong>استبدال جميع البيانات الحالية</strong> بالكامل. هذا الإجراء لا يمكن التراجع عنه.
        </div>

        <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data"
              onsubmit="return confirm('هل أنت متأكد؟ سيتم استبدال جميع البيانات الحالية!')">
            @csrf
            <div class="form-group" style="margin-bottom:16px">
                <label style="font-size:.88rem;font-weight:600">اختر ملف النسخة الاحتياطية (.zip)</label>
                <input type="file" name="backup_file" accept=".tar.gz,.tgz" required
                       style="display:block;margin-top:8px;width:100%;font-size:.88rem">
            </div>
            <button type="submit" style="background:#e53935;color:#fff;border:none;padding:10px 22px;border-radius:8px;cursor:pointer;font-family:inherit;font-size:.92rem;display:inline-flex;align-items:center;gap:8px">
                <i class="fas fa-upload"></i> استيراد واستعادة
            </button>
        </form>
    </div>

</div>
@endsection
