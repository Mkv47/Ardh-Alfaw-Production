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

            <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap">
                <button id="importBtn" onclick="startImport()" class="btn btn-delete">
                    <i class="fas fa-upload"></i> استيراد واستعادة
                </button>
                <button id="retryBtn" onclick="startImport()" class="btn btn-primary" style="display:none">
                    <i class="fas fa-redo"></i> إعادة المحاولة
                </button>
            </div>
        </div>
    </div>

</div>

<script>
const CHUNK_SIZE = 8 * 1024 * 1024; // 8 MB per chunk (safe for shared hosting)
const CHUNK_URL  = '{{ route('admin.backup.import.chunk') }}';
const FINAL_URL  = '{{ route('admin.backup.import.finalize') }}';
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;

// Persisted across retries
let _uploadId    = null;
let _totalChunks = 0;
let _lastChunk   = -1; // last successfully uploaded chunk index

async function startImport() {
    const file = document.getElementById('backupFile').files[0];
    if (!file) { alert('اختر ملفاً أولاً'); return; }

    const isRetry = _uploadId !== null && _lastChunk >= 0;

    if (!isRetry && !confirm('هل أنت متأكد؟ سيتم استبدال جميع البيانات الحالية!')) return;

    const btn = document.getElementById('importBtn');

    // Fresh start or retry with same session
    if (!isRetry) {
        _uploadId    = Date.now() + '_' + Math.random().toString(36).slice(2);
        _totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        _lastChunk   = -1;
    }

    const resumeFrom = _lastChunk + 1;

    btn.disabled = true;
    document.getElementById('retryBtn').style.display   = 'none';
    document.getElementById('progressWrap').style.display = 'block';
    document.getElementById('progressBar').style.background = 'linear-gradient(90deg,var(--teal),var(--navy))';

    setProgress(
        Math.round((resumeFrom / _totalChunks) * 90),
        isRetry ? `استئناف من القطعة ${resumeFrom}...` : 'جاري الرفع...',
        `${resumeFrom} / ${_totalChunks} قطعة`
    );

    try {
        for (let i = resumeFrom; i < _totalChunks; i++) {
            const start = i * CHUNK_SIZE;
            const blob  = file.slice(start, start + CHUNK_SIZE);
            const form  = new FormData();
            form.append('_token',       CSRF);
            form.append('upload_id',    _uploadId);
            form.append('chunk_index',  i);
            form.append('total_chunks', _totalChunks);
            form.append('chunk',        blob, 'chunk');

            await uploadChunk(form);
            _lastChunk = i;
            setProgress(
                Math.round(((i + 1) / _totalChunks) * 90),
                'جاري الرفع...',
                `${i + 1} / ${_totalChunks} قطعة`
            );
        }

        // ── Step 1: Extract archive ──────────────────────────────────────────
        setProgress(92, 'الخطوة 1/2: فك الضغط...', 'يتم فك ضغط الأرشيف...');
        await finalizeStep(1);

        // ── Step 2: Restore DB + storage ────────────────────────────────────
        setProgress(96, 'الخطوة 2/2: استعادة قاعدة البيانات...', 'يتم استيراد البيانات والملفات...');
        await finalizeStep(2);

        setProgress(100, 'اكتملت الاستعادة!', 'تم استيراد النسخة الاحتياطية بنجاح.');
        document.getElementById('progressBar').style.background = '#16a34a';
        _uploadId = null; _lastChunk = -1;
        setTimeout(() => location.reload(), 1500);

    } catch (err) {
        document.getElementById('progressLabel').textContent =
            `حدث خطأ عند القطعة ${_lastChunk + 2}: ${err.message}`;
        document.getElementById('progressBar').style.background = '#dc2626';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> استيراد واستعادة';
        document.getElementById('retryBtn').style.display = 'inline-flex';
        document.getElementById('retryBtn').innerHTML =
            `<i class="fas fa-redo"></i> استئناف من القطعة ${_lastChunk + 2}`;
    }
}

async function finalizeStep(step) {
    const res  = await fetch(FINAL_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ upload_id: _uploadId, total_chunks: _totalChunks, step }),
    });
    const json = await res.json();
    if (!res.ok || json.error) {
        throw new Error((json.error || 'فشل الاستعادة') + (json.path ? ' [' + json.path + ']' : ''));
    }
    return json;
}

function uploadChunk(formData, attempt = 1) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', CHUNK_URL);
        xhr.timeout = 60000; // 60s per chunk
        xhr.onload = () => {
            try {
                const json = JSON.parse(xhr.responseText);
                json.error ? reject(new Error(json.error)) : resolve(json);
            } catch(e) {
                reject(new Error('استجابة غير صالحة من الخادم (HTTP ' + xhr.status + ')'));
            }
        };
        xhr.onerror   = () => attempt < 3
            ? uploadChunk(formData, attempt + 1).then(resolve).catch(reject)
            : reject(new Error('خطأ في الشبكة بعد 3 محاولات'));
        xhr.ontimeout = () => attempt < 3
            ? uploadChunk(formData, attempt + 1).then(resolve).catch(reject)
            : reject(new Error('انتهت مهلة الرفع'));
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
