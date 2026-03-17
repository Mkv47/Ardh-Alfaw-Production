<div class="cert-card reveal" onclick="openCertModal('{{ Storage::url($cert->file) }}', '{{ addslashes($cert->title) }}')">
    <div class="cert-thumb">
        @if($cert->thumbnail)
            <img src="{{ Storage::url($cert->thumbnail) }}" alt="{{ $cert->title }}">
        @else
            <div class="cert-no-thumb">
                <i class="fas fa-certificate"></i>
            </div>
        @endif
        <div class="cert-hover-overlay">
            <i class="fas fa-search-plus"></i>
            <span>عرض الشهادة</span>
        </div>
    </div>
    <div class="cert-label">{{ $cert->title }}</div>
</div>
