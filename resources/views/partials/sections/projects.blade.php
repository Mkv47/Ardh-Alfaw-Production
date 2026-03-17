<section class="section projects" id="projects">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">مشاريعنا</span>
            <h2 class="section-title">أعمالنا المنجزة</h2>
            <p class="section-desc">نماذج من المشاريع التي نفذناها بنجاح</p>
        </div>
        <div class="projects-filter">
            <button class="filter-btn active" data-filter="all">الكل</button>
            <button class="filter-btn" data-filter="marine">بحري</button>
            <button class="filter-btn" data-filter="transport">نقل</button>
            <button class="filter-btn" data-filter="supply">توريد</button>
            <button class="filter-btn" data-filter="contracts">مقاولات</button>
        </div>
        <div class="projects-grid">
            @foreach($projects as $project)
            <div class="project-card reveal {{ $loop->index >= 6 ? 'extra-item' : '' }}" data-category="{{ $project->category_key }}">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}">
                    @else
                        <i class="{{ $project->icon }}"></i>
                    @endif
                </div>
                <div class="project-content">
                    <span class="project-category">{{ $project->category_label }}</span>
                    <h3>{{ $project->title }}</h3>
                    <p>{{ $project->description }}</p>
                    <div class="project-meta">
                        <span><i class="fas fa-building"></i> {{ $project->client }}</span>
                        <span><i class="fas fa-calendar"></i> {{ $project->year }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($projects->count() > 6)
        <div class="show-more-wrap">
            <button class="show-more-btn" data-target=".projects-grid">
                عرض المزيد ({{ $projects->count() - 6 }}) <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>
