<section class="section news" id="news">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">الأخبار</span>
            <h2 class="section-title">آخر الأخبار والإعلانات</h2>
            <p class="section-desc">تابع أحدث أخبار وإعلانات شركة أرض الفاو</p>
        </div>
        <div class="news-grid">
            @foreach($news as $item)
            <article class="news-card reveal {{ $loop->index >= 6 ? 'extra-item' : '' }}">
                <div class="news-image">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}">
                    @else
                        <i class="{{ $item->icon }}"></i>
                    @endif
                    <span class="news-badge">{{ $item->badge }}</span>
                </div>
                <div class="news-body">
                    <div class="news-meta">
                        <span><i class="fas fa-calendar-alt"></i> {{ $item->published_at->format('d/m/Y') }}</span>
                        <span class="news-cat">{{ $item->category }}</span>
                    </div>
                    <h3>{{ $item->title }}</h3>
                    <button class="news-link news-read-more"
                        data-title="{{ $item->title }}"
                        data-date="{{ $item->published_at->format('d/m/Y') }}"
                        data-category="{{ $item->category }}"
                        data-badge="{{ $item->badge }}"
                        data-excerpt="{{ $item->excerpt }}"
                        data-image="{{ $item->image ? Storage::url($item->image) : '' }}"
                        data-icon="{{ $item->icon }}">
                        اقرأ المزيد <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </article>
            @endforeach
        </div>
        @if($news->count() > 6)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".news-grid">
                عرض المزيد ({{ $news->count() - 6 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
