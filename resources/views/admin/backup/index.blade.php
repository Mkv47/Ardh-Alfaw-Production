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
            <h2><i class="fas fa-download"></i> تصدير النسخة الاحتياطية</h2>
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
                <i class="fas fa-file-archive"></i> تحميل النسخة الاحتياطية (.tar.gz)
            </a>
        </div>
    </div>

    {{-- ── Import ── --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-upload"></i> استيراد نسخة احتياطية</h2>
        </div>
        <div class="card-body">

            <div class="alert alert-danger" style="margin-bottom:20px">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>تحذير:</strong> سيؤدي الاستيراد إلى <strong>استبدال جميع البيانات الحالية</strong>. لا يمكن التراجع عنه.
            </div>

            <div class="form-group">
                <label class="form-label">ملف النسخة الاحتياطية (.tar.gz) — حتى 10 جيجابايت</label>
                <input type="file" id="backupFile" accept=".tar.gz,.tgz" class="form-control">
            </div>

            {{-- Progress --}}
            <div id="progressWrap" style="display:none;margin-top:18px">
                <div style="display:flex;justify-content:space-between;font-size:.82rem;color:var(--gray);margin-bottom:6px">
                    <span id="progressLabel">جاري الرفع...</span>
                    <span id="progressPct">0%</span>
                </div>
                <div style="background:#e9ecef;border-radius:8px;height:12px;overflow:hidden">
                    <div id="progressBar"
                         style="height:100%;width:0%;background:linear-gradient(90deg,var(--teal),var(--navy));border-radius:8px;transition:width .15s ease"></div>
                </div>
                <p id="progressStatus" style="font-size:.82rem;color:var(--gray);margin-top:8px"></p>
            </div>

            <button id="importBtn" onclick="startImport()" class="btn btn-delete" style="margin-top:16px">
                <i class="fas fa-upload"></i> استيراد واستعادة
            </button>
        </div>
    </div>

</div>

<script>
const CHUNK_SIZE  = 50 * 1024 * 1024; // 50 MB per chunk
const CHUNK_URL   = '{{ route('admin.backup.import.chunk') }}';
const FINAL_URL   = '{{ route('admin.backup.import.finalize') }}';
const CSRF        = document.querySelector('meta[name="csrf-token"]').content;

async function startImport() {
    const file = document.getElementById('backupFile').files[0];
    if (!file) { alert('اختر ملفاً أولاً'); return; }
    if (!confirm('هل أنت متأكد؟ سيتم استبدال جميع البيانات الحالية!')) return;

    const btn        = document.getElementById('importBtn');
    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
    const uploadId   = Date.now() + '_' + Math.random().toString(36).slice(2);

    btn.disabled = true;
    document.getElementById('progressWrap').style.display = 'block';
    setProgress(0, 'جاري الرفع...', `0 / ${totalChunks} قطعة`);

    try {
        for (let i = 0; i < totalChunks; i++) {
            const start  = i * CHUNK_SIZE;
            const blob   = file.slice(start, start + CHUNK_SIZE);
            const form   = new FormData();
            form.append('_token',       CSRF);
            form.append('upload_id',    uploadId);
            form.append('chunk_index',  i);
            form.append('total_chunks', totalChunks);
            form.append('chunk',        blob, 'chunk');

            await uploadChunk(form);
            setProgress(
                Math.round(((i + 1) / totalChunks) * 90),
                'جاري الرفع...',
                `${i + 1} / ${totalChunks} قطعة`
            );
        }

        setProgress(92, 'جاري تجميع الملف...', 'يتم دمج القطع...');

        const res  = await fetch(FINAL_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ upload_id: uploadId, total_chunks: totalChunks }),
        });
        const json = await res.json();

        if (!res.ok || json.error) throw new Error(json.error || 'فشل الاستعادة');

        setProgress(100, 'اكتملت الاستعادة!', 'تم استيراد النسخة الاحتياطية بنجاح.');
        document.getElementById('progressBar').style.background = '#16a34a';
        setTimeout(() => location.reload(), 1500);

    } catch (err) {
        document.getElementById('progressLabel').textContent = 'حدث خطأ: ' + err.message;
        document.getElementById('progressBar').style.background = '#dc2626';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> استيراد واستعادة';
    }
}

function uploadChunk(formData) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', CHUNK_URL);
        xhr.onload = () => {
            const json = JSON.parse(xhr.responseText);
            json.error ? reject(new Error(json.error)) : resolve(json);
        };
        xhr.onerror = () => reject(new Error('خطأ في الشبكة'));
        xhr.send(formData);
    });
}

function setProgress(pct, label, status) {
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressPct').textContent  = pct + '%';
    document.getElementById('progressLabel').textContent = label;
    document.getElementById('progressStatus').textContent = status;
}
</script>

@endsection
