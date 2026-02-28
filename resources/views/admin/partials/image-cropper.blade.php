{{--
    Image Cropper Partial
    Variables:
      $currentImage  — current stored path (optional)
      $inputId       — unique id prefix, default 'image'
      $folder        — storage subfolder hint (unused, for context only)
--}}
@php $inputId = $inputId ?? 'image'; @endphp

@if(!empty($currentImage))
    <div style="margin-bottom:10px">
        <img src="{{ Storage::url($currentImage) }}" alt="صورة حالية"
             style="max-height:140px;border-radius:8px;border:2px solid #e2e8f0;">
    </div>
    <label style="font-weight:400;font-size:.9rem;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px">
        <input type="checkbox" name="remove_image" value="1"> حذف الصورة الحالية
    </label><br>
@endif

{{-- Base64 cropped output sent to server --}}
<input type="hidden" name="image_cropped" id="{{ $inputId }}Cropped">

{{-- File picker – no name, data flows via hidden input --}}
<input type="file" id="{{ $inputId }}File" accept="image/*" style="margin-bottom:8px">
<small style="color:var(--gray);display:block;margin-bottom:12px">
    اتركه فارغاً للإبقاء على الصورة الحالية
</small>

{{-- Cropper UI --}}
<div id="{{ $inputId }}CropperArea" style="display:none">
    <div style="border:2px solid var(--border);border-radius:10px;overflow:hidden;background:#111;max-height:420px">
        <img id="{{ $inputId }}CropperImg" style="display:block;max-width:100%">
    </div>
    <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <button type="button" class="btn btn-primary" onclick="applyCrop_{{ $inputId }}()">
            <i class="fas fa-crop-alt"></i> تطبيق القص
        </button>
        <button type="button" class="btn btn-back" onclick="cancelCrop_{{ $inputId }}()">
            <i class="fas fa-times"></i> إلغاء
        </button>
        <div style="display:flex;gap:6px;margin-right:auto">
            <button type="button" class="btn btn-edit btn-sm" title="تكبير"      onclick="croppers['{{ $inputId }}'].zoom(0.1)"><i class="fas fa-search-plus"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="تصغير"      onclick="croppers['{{ $inputId }}'].zoom(-0.1)"><i class="fas fa-search-minus"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="دوران يسار" onclick="croppers['{{ $inputId }}'].rotate(-90)"><i class="fas fa-undo"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="دوران يمين" onclick="croppers['{{ $inputId }}'].rotate(90)"><i class="fas fa-redo"></i></button>
            <button type="button" class="btn btn-edit btn-sm" title="إعادة ضبط"  onclick="croppers['{{ $inputId }}'].reset()"><i class="fas fa-sync"></i></button>
        </div>
    </div>
</div>

{{-- Cropped result preview --}}
<div id="{{ $inputId }}CroppedPreviewArea" style="display:none;margin-top:14px">
    <p style="font-size:.85rem;color:var(--gray);margin-bottom:6px">معاينة الصورة بعد القص:</p>
    <img id="{{ $inputId }}CroppedPreview" style="max-height:160px;border-radius:8px;border:2px solid var(--teal)">
    <div style="margin-top:8px">
        <button type="button" class="btn btn-edit btn-sm" onclick="reopenCropper_{{ $inputId }}()">
            <i class="fas fa-crop-alt"></i> تعديل القص
        </button>
    </div>
</div>

{{-- Load Cropper.js once per page --}}
@once
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        const croppers = {};
        const originalSrcs = {};

        function initCropperInstance(id, src) {
            const img = document.getElementById(id + 'CropperImg');
            img.src = src;
            document.getElementById(id + 'CropperArea').style.display = 'block';
            document.getElementById(id + 'CroppedPreviewArea').style.display = 'none';
            document.getElementById(id + 'Cropped').value = '';
            if (croppers[id]) { croppers[id].destroy(); }
            croppers[id] = new Cropper(img, {
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }
    </script>
@endonce

{{-- Per-instance JS --}}
<script>
(function () {
    const id = '{{ $inputId }}';

    document.getElementById(id + 'File').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            originalSrcs[id] = ev.target.result;
            initCropperInstance(id, ev.target.result);
        };
        reader.readAsDataURL(file);
    });

    window['applyCrop_' + id] = function () {
        if (!croppers[id]) return;
        const canvas = croppers[id].getCroppedCanvas({
            maxWidth: 1200, maxHeight: 1200,
            imageSmoothingEnabled: true, imageSmoothingQuality: 'high',
        });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.88);
        document.getElementById(id + 'Cropped').value = dataUrl;
        document.getElementById(id + 'CroppedPreview').src = dataUrl;
        document.getElementById(id + 'CroppedPreviewArea').style.display = 'block';
        document.getElementById(id + 'CropperArea').style.display = 'none';
    };

    window['cancelCrop_' + id] = function () {
        document.getElementById(id + 'CropperArea').style.display = 'none';
        document.getElementById(id + 'CroppedPreviewArea').style.display = 'none';
        document.getElementById(id + 'File').value = '';
        document.getElementById(id + 'Cropped').value = '';
        if (croppers[id]) { croppers[id].destroy(); delete croppers[id]; }
        delete originalSrcs[id];
    };

    window['reopenCropper_' + id] = function () {
        if (originalSrcs[id]) initCropperInstance(id, originalSrcs[id]);
    };
})();
</script>
