{{--
    Logo Cropper Partial — circular 1:1 crop with live preview
    Variables:
      $currentImage  — current stored path (optional)
      $inputId       — unique JS id prefix, e.g. 'navLogo' or 'heroLogo'
      $hiddenName    — name of the hidden base64 input sent to server
--}}
@php
    $inputId    = $inputId    ?? 'logo';
    $hiddenName = $hiddenName ?? 'logo_cropped';
@endphp

{{-- Current image preview --}}
@if(!empty($currentImage))
    <div style="margin-bottom:12px">
        <img src="{{ Storage::url($currentImage) }}" alt="الشعار الحالي"
             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--teal);background:#f8f9fa;">
        <p style="font-size:.8rem;color:var(--gray);margin-top:4px">الشعار الحالي</p>
    </div>
@endif

{{-- Hidden base64 field sent to server --}}
<input type="hidden" name="{{ $hiddenName }}" id="{{ $inputId }}Cropped">

{{-- File picker --}}
<div style="margin-bottom:8px">
    <input type="file" id="{{ $inputId }}File" accept="image/*">
</div>
<small style="color:var(--gray);display:block;margin-bottom:14px">
    اترك الحقل فارغاً للإبقاء على الشعار الحالي
</small>

{{-- Cropper UI --}}
<div id="{{ $inputId }}CropperArea" style="display:none;margin-bottom:12px">
    <div style="border:2px solid var(--border);border-radius:10px;overflow:hidden;background:#111;max-height:380px;margin-bottom:12px">
        <img id="{{ $inputId }}CropperImg" style="display:block;max-width:100%">
    </div>
    {{-- Controls --}}
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <button type="button" class="btn btn-primary" onclick="lcApply_{{ $inputId }}()">
            <i class="fas fa-crop-alt"></i> تطبيق القص
        </button>
        <button type="button" class="btn btn-back" onclick="lcCancel_{{ $inputId }}()">
            <i class="fas fa-times"></i> إلغاء
        </button>
        <div style="display:flex;gap:6px;margin-right:auto">
            <button type="button" class="btn btn-edit btn-sm" title="تكبير"      onclick="lcCroppers['{{ $inputId }}'].zoom(0.1)"><i class="fas fa-search-plus"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="تصغير"      onclick="lcCroppers['{{ $inputId }}'].zoom(-0.1)"><i class="fas fa-search-minus"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="دوران يسار" onclick="lcCroppers['{{ $inputId }}'].rotate(-90)"><i class="fas fa-undo"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="دوران يمين" onclick="lcCroppers['{{ $inputId }}'].rotate(90)"><i class="fas fa-redo"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="إعادة ضبط"  onclick="lcCroppers['{{ $inputId }}'].reset()"><i class="fas fa-sync"></i></button>
        </div>
    </div>
</div>

{{-- Result preview --}}
<div id="{{ $inputId }}CroppedPreviewArea" style="display:none;margin-bottom:12px">
    <p style="font-size:.85rem;color:var(--gray);margin-bottom:6px">الشعار بعد القص:</p>
    <img id="{{ $inputId }}CroppedPreview"
         style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--teal)">
    <div style="margin-top:8px">
        <button type="button" class="btn btn-edit btn-sm" onclick="lcReopen_{{ $inputId }}()">
            <i class="fas fa-crop-alt"></i> تعديل القص
        </button>
    </div>
</div>

{{-- Load Cropper.js once for the whole page --}}
@once
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        window.lcCroppers    = {};
        window.lcOriginalSrcs = {};
    </script>
@endonce

{{-- Per-instance JS --}}
<script>
(function () {
    const id = '{{ $inputId }}';

    function init(src) {
        const img = document.getElementById(id + 'CropperImg');
        img.src = src;
        document.getElementById(id + 'CropperArea').style.display = 'block';
        document.getElementById(id + 'CroppedPreviewArea').style.display = 'none';
        document.getElementById(id + 'Cropped').value = '';
        if (window.lcCroppers[id]) { window.lcCroppers[id].destroy(); }
        window.lcCroppers[id] = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    }

    document.getElementById(id + 'File').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            window.lcOriginalSrcs[id] = ev.target.result;
            init(ev.target.result);
        };
        reader.readAsDataURL(file);
    });

    window['lcApply_' + id] = function () {
        if (!window.lcCroppers[id]) return;
        const canvas = window.lcCroppers[id].getCroppedCanvas({
            width: 400, height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        const dataUrl = canvas.toDataURL('image/png');
        document.getElementById(id + 'Cropped').value = dataUrl;
        document.getElementById(id + 'CroppedPreview').src = dataUrl;
        document.getElementById(id + 'CroppedPreviewArea').style.display = 'block';
        document.getElementById(id + 'CropperArea').style.display = 'none';
    };

    window['lcCancel_' + id] = function () {
        document.getElementById(id + 'CropperArea').style.display = 'none';
        document.getElementById(id + 'CroppedPreviewArea').style.display = 'none';
        document.getElementById(id + 'File').value = '';
        document.getElementById(id + 'Cropped').value = '';
        if (window.lcCroppers[id]) { window.lcCroppers[id].destroy(); delete window.lcCroppers[id]; }
        delete window.lcOriginalSrcs[id];
    };

    window['lcReopen_' + id] = function () {
        if (window.lcOriginalSrcs[id]) init(window.lcOriginalSrcs[id]);
    };
})();
</script>
