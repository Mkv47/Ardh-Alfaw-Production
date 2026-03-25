@extends('admin.layout')
@section('page-title', 'النسخ الاحتياطي')

@section('content')

<div class="page-head">
    <h2><i class="fas fa-database" style="color:var(--teal)"></i> النسخ الاحتياطي والاستيراد</h2>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

    {{-- ── Export ── --}}
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-download"></i>
                تصدير النسخة الاحتياطية
            </h2>
        </div>
        <div class="card-body">
            <p style="font-size:.9rem;color:var(--gray);margin-bottom:16px;line-height:1.7">
                يشمل الملف المُصدَّر جميع بيانات قاعدة البيانات والصور والملفات المرفوعة.
            </p>
            <ul style="font-size:.85rem;color:var(--gray);line-height:2.2;padding-right:20px;margin-bottom:24px">
                <li>الخدمات، المشاريع، الأخبار، الفريق</li>
                <li>المناقصات، العملاء، المعرض، الشهادات</li>
                <li>الإعدادات، الرسائل، المديرون</li>
                <li>جميع الصور والملفات المرفوعة</li>
            </ul>
            <a href="{{ route('admin.backup.export') }}" class="btn btn-primary">
                <i class="fas fa-file-archive"></i>
                تحميل النسخة الاحتياطية (.tar.gz)
            </a>
        </div>
    </div>

    {{-- ── Import ── --}}
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-upload"></i>
                استيراد نسخة احتياطية
            </h2>
        </div>
        <div class="card-body">

            <div class="alert alert-danger" style="margin-bottom:20px">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>تحذير:</strong> سيؤدي الاستيراد إلى <strong>استبدال جميع البيانات الحالية</strong> بالكامل. هذا الإجراء لا يمكن التراجع عنه.
            </div>

            <form action="{{ route('admin.backup.import') }}" method="POST" enctype="multipart/form-data"
                  onsubmit="return confirm('هل أنت متأكد؟ سيتم استبدال جميع البيانات الحالية!')">
                @csrf
                <div class="form-group">
                    <label class="form-label">ملف النسخة الاحتياطية (.tar.gz)</label>
                    <input type="file" name="backup_file" accept=".tar.gz,.tgz" required class="form-control">
                </div>
                <button type="submit" class="btn btn-delete" style="margin-top:8px">
                    <i class="fas fa-upload"></i> استيراد واستعادة
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
