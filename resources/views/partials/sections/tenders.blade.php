<section class="section tenders" id="tenders">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">المناقصات</span>
            <h2 class="section-title">المناقصات والمشتريات</h2>
            <p class="section-desc">فرص التعاون والعطاءات المفتوحة</p>
        </div>
        <div class="tenders-list">
            @foreach($tenders as $tender)
            <div class="tender-card reveal {{ $loop->index >= 6 ? 'extra-item' : '' }}">
                <div class="tender-status {{ $tender->status === 'open' ? 'open' : 'closed' }}">
                    {{ $tender->status === 'open' ? 'مفتوحة' : 'منتهية' }}
                </div>
                <div class="tender-body">
                    <h3>{{ $tender->title }}</h3>
                    <p>{{ $tender->description }}</p>
                    <div class="tender-meta">
                        <span><i class="fas fa-calendar"></i> {{ $tender->status === 'open' ? 'تاريخ الإغلاق:' : 'تم الإغلاق:' }} {{ $tender->deadline->format('d/m/Y') }}</span>
                        <span><i class="fas fa-tag"></i> {{ $tender->type }}</span>
                    </div>
                </div>
                @if($tender->status === 'open')
                <a href="#contact" class="btn btn-primary tender-btn">طلب المشاركة</a>
                @else
                <span class="btn btn-outline tender-btn disabled">مغلقة</span>
                @endif
            </div>
            @endforeach
        </div>
        @if($tenders->count() > 6)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".tenders-list">
                عرض المزيد ({{ $tenders->count() - 6 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
