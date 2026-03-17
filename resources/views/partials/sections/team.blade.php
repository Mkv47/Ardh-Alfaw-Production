<section class="section team" id="team">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">فريقنا</span>
            <h2 class="section-title">فريق العمل</h2>
            <p class="section-desc">كوادر بشرية متخصصة تقود مسيرة النجاح</p>
        </div>
        <div class="team-grid">
            @foreach($teamMembers as $member)
            <div class="team-card reveal {{ $loop->index >= 8 ? 'extra-item' : '' }}">
                <div class="team-avatar">
                    @if($member->image)
                        <img src="{{ Storage::url($member->image) }}" alt="{{ $member->name }}">
                    @else
                        <i class="{{ $member->icon }}"></i>
                    @endif
                </div>
                <div class="team-info">
                    <h3>{{ $member->name }}</h3>
                    <span class="team-role">{{ $member->role }}</span>
                    <p>{{ $member->bio }}</p>
                    <div class="team-social">
                        <a href="#contact" aria-label="تواصل"><i class="fas fa-envelope"></i></a>
                        @if($member->whatsapp)
                        <a href="https://wa.me/{{ $member->whatsapp }}" target="_blank" aria-label="واتساب"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($teamMembers->count() > 8)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".team-grid">
                عرض المزيد ({{ $teamMembers->count() - 8 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
