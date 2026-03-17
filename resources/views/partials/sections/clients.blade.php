<section class="section clients" id="clients">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">شركاؤنا</span>
            <h2 class="section-title">عملاؤنا ومشاركونا</h2>
            <p class="section-desc">نفتخر بالعمل مع أهم المؤسسات في العراق</p>
        </div>
        <div class="clients-grid">
            @foreach($clients as $client)
            <div class="client-card reveal {{ $loop->index >= 6 ? 'extra-item' : '' }}">
                <i class="{{ $client->icon }}"></i>
                <h4>{{ $client->name }}</h4>
            </div>
            @endforeach
        </div>
        @if($clients->count() > 6)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".clients-grid">
                عرض المزيد ({{ $clients->count() - 6 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
