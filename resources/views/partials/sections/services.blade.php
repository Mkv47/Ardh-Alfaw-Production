<section class="section services" id="services">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">خدماتنا</span>
            <h2 class="section-title">ماذا نقدم</h2>
            <p class="section-desc">نقدم مجموعة متكاملة من الخدمات اللوجستية والبحرية</p>
        </div>
        <div class="services-grid">
            @foreach($services as $service)
            <div class="service-card reveal {{ $loop->index >= 6 ? 'extra-item' : '' }}">
                <div class="service-icon"><i class="{{ $service->icon }}"></i></div>
                <h3>{{ $service->title }}</h3>
                <p>{{ $service->description }}</p>
            </div>
            @endforeach
        </div>
        @if($services->count() > 6)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".services-grid">
                عرض المزيد ({{ $services->count() - 6 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
