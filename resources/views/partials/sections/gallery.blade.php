<section class="section gallery" id="gallery">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">معرض الصور</span>
            <h2 class="section-title">لقطات من أعمالنا</h2>
            <p class="section-desc">صور توثق أعمالنا ومشاريعنا الميدانية</p>
        </div>
        <div class="gallery-grid">
            @foreach($galleryItems as $item)
            <div class="gallery-item reveal {{ $loop->index >= 15 ? 'extra-item' : '' }}" data-lightbox="{{ $item->key }}">
                <div class="gallery-thumb">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->caption }}">
                    @else
                        <i class="{{ $item->icon }}"></i>
                    @endif
                </div>
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                    <span>{{ $item->caption }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @if($galleryItems->count() > 15)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".gallery-grid">
                عرض المزيد ({{ $galleryItems->count() - 15 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
        <div class="lightbox-content">
            <div class="lightbox-icon"></div>
            <p class="lightbox-caption"></p>
        </div>
    </div>
</section>
