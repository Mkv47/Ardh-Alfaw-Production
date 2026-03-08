@extends('layouts.app')

@section('content')

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#home" class="logo">
                @if(!empty($s['logo']))
                    <img src="{{ Storage::url($s['logo']) }}" alt="أرض الفاو" class="logo-img">
                @else
                    <i class="fas fa-anchor"></i>
                @endif
                <span>أرض الفاو</span>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">الرئيسية</a></li>
                <li><a href="#about">من نحن</a></li>
                <li><a href="#services">خدماتنا</a></li>
                <li><a href="#projects">مشاريعنا</a></li>
                <li><a href="#news">الأخبار</a></li>
                <li><a href="#team">فريقنا</a></li>
                <li><a href="#gallery">معرض الصور</a></li>
                <li><a href="#tenders">المناقصات</a></li>
                <li><a href="#clients">عملاؤنا</a></li>
                <li><a href="#contact">تواصل معنا</a></li>
            </ul>
        </div>
    </nav>
    <!-- Nav overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- Hero Section -->
    @php $heroLogo = !empty($s['hero_logo']) ? $s['hero_logo'] : ($s['logo'] ?? null); @endphp
    <section class="hero" id="home">
        <div class="hero-particles" id="particles"></div>
        @if($heroLogo)
            @php
                $heroLogoSize    = $s['hero_logo_size'] ?? 600;
                $heroLogoOpacity = isset($s['hero_logo_opacity']) ? $s['hero_logo_opacity'] / 100 : 0.15;
            @endphp
            <img src="{{ Storage::url($heroLogo) }}" alt="" class="hero-bg-logo" aria-hidden="true"
                 style="width:{{ $heroLogoSize }}px;height:{{ $heroLogoSize }}px;opacity:{{ $heroLogoOpacity }}">
        @endif
        <div class="hero-content">
            <h1 class="hero-title animate-fade-up">{{ $s['hero_title'] ?? 'شركة أرض الفاو' }}</h1>
            <p class="hero-subtitle animate-fade-up delay-1">{{ $s['hero_subtitle'] ?? 'للنقل والخدمات البحرية' }}</p>
            <p class="hero-location animate-fade-up delay-2">
                <i class="fas fa-map-marker-alt"></i>
                {{ $s['hero_location'] ?? 'البصرة، العراق' }}
            </p>
            <div class="hero-stats animate-fade-up delay-3">
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $s['hero_stat_years'] ?? 7 }}">0</span>+
                    <span class="stat-label">سنوات خبرة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $s['hero_stat_fields'] ?? 6 }}">0</span>-
                    <span class="stat-label">مجالات عمل</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="{{ $s['hero_stat_clients'] ?? 4 }}">0</span>+
                    <span class="stat-label">عملاء حكوميين</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="100">0</span>%
                    <span class="stat-label">التزام بالجودة</span>
                </div>
            </div>
            <div class="hero-buttons animate-fade-up delay-4">
                <a href="#services" class="btn btn-primary">اكتشف خدماتنا</a>
                <a href="#contact" class="btn btn-outline">تواصل معنا</a>
            </div>
        </div>
        <div class="hero-scroll">
            <a href="#about">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about" id="about">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">من نحن</span>
                <h2 class="section-title">نبذة عن الشركة</h2>
            </div>
            <div class="about-content">
                <div class="about-text reveal">
                    <p>
                        {!! nl2br(e($s['about_text_1'] ?? 'شركة أرض الفاو للنقل والخدمات البحرية هي شركة عراقية رائدة تأسست عام 2018 في مدينة البصرة.')) !!}
                    </p>
                    <p>
                        {!! nl2br(e($s['about_text_2'] ?? 'نفتخر بشراكاتنا مع كبرى المؤسسات الحكومية والقطاع الخاص.')) !!}
                    </p>
                </div>
                <div class="about-values reveal">
                    <div class="value-card">
                        <i class="fas fa-medal"></i>
                        <h3>الاحترافية</h3>
                        <p>نعمل بأعلى معايير الاحترافية في جميع خدماتنا</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>السلامة</h3>
                        <p>السلامة أولاً في جميع عملياتنا</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-gem"></i>
                        <h3>الجودة</h3>
                        <p>نلتزم بأعلى معايير الجودة العالمية</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-handshake"></i>
                        <h3>المصداقية</h3>
                        <p>الشفافية والمصداقية في التعامل</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
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
                    <div class="service-icon">
                        <i class="{{ $service->icon }}"></i>
                    </div>
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

    <!-- Projects Section -->
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

    <!-- News Section -->
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

    <!-- Team Section -->
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

    <!-- Gallery Section -->
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
        <!-- Lightbox -->
        <div class="lightbox" id="lightbox">
            <button class="lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
            <div class="lightbox-content">
                <div class="lightbox-icon"></div>
                <p class="lightbox-caption"></p>
            </div>
        </div>
    </section>

    <!-- Tenders Section -->
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
                            <span>
                                <i class="fas fa-calendar"></i>
                                {{ $tender->status === 'open' ? 'تاريخ الإغلاق:' : 'تم الإغلاق:' }}
                                {{ $tender->deadline->format('d/m/Y') }}
                            </span>
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

    <!-- Clients Section -->
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

    <!-- Contact Section -->
    <section class="section contact" id="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">تواصل معنا</span>
                <h2 class="section-title">نحن هنا لخدمتك</h2>
                <p class="section-desc">تواصل معنا للاستفسار أو طلب خدماتنا</p>
            </div>
            <div class="contact-wrapper">
                <div class="contact-info reveal">
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>المقر الرئيسي</h4>
                            <p>{{ $s['contact_address_main'] ?? 'البصرة - البراضعية - شارع سيد أمين' }}</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-building"></i>
                        <div>
                            <h4>الفرع</h4>
                            <p>{{ $s['contact_address_branch'] ?? 'البصرة - الجزأر - مقابل مركز فينيسيا' }}</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>الهاتف</h4>
                            <p dir="ltr">{{ $s['contact_phone_1'] ?? '07845000007' }}</p>
                            <p dir="ltr">{{ $s['contact_phone_2'] ?? '07724300004' }}</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>البريد الإلكتروني</h4>
                            <p>{{ $s['contact_email'] ?? 'info@ardhfalfaw.com' }}</p>
                        </div>
                    </div>
                    <a href="https://wa.me/{{ $s['contact_whatsapp'] ?? '9647845000007' }}" target="_blank" class="whatsapp-btn">
                        <i class="fab fa-whatsapp"></i>
                        تواصل عبر واتساب
                    </a>
                </div>
                <form class="contact-form reveal" id="contactForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">الاسم الكامل *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">البريد الإلكتروني *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">رقم الهاتف</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company">اسم الشركة</label>
                        <input type="text" id="company" name="company">
                    </div>
                    <div class="form-group">
                        <label for="subject">الموضوع *</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">الرسالة *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-paper-plane"></i>
                        إرسال الرسالة
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <a href="#home" class="logo">
                        @if(!empty($s['logo']))
                            <img src="{{ Storage::url($s['logo']) }}" alt="أرض الفاو" class="logo-img">
                        @else
                            <i class="fas fa-anchor"></i>
                        @endif
                        <span>أرض الفاو</span>
                    </a>
                    <p>{{ $s['footer_description'] ?? 'شركة عراقية رائدة في مجال النقل والخدمات البحرية، تأسست عام 2018 في البصرة.' }}</p>
                </div>
                <div class="footer-links">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="#home">الرئيسية</a></li>
                        <li><a href="#about">من نحن</a></li>
                        <li><a href="#services">خدماتنا</a></li>
                        <li><a href="#projects">مشاريعنا</a></li>
                        <li><a href="#news">الأخبار</a></li>
                        <li><a href="#team">فريقنا</a></li>
                        <li><a href="#tenders">المناقصات</a></li>
                        <li><a href="#contact">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="footer-services">
                    <h4>خدماتنا</h4>
                    <ul>
                        <li>النقل البحري</li>
                        <li>النقل البري</li>
                        <li>الشحن والتفريغ</li>
                        <li>الاستيراد والتصدير</li>
                        <li>المقاولات الكهربائية</li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>تواصل معنا</h4>
                    <p><i class="fas fa-phone"></i> {{ $s['contact_phone_1'] ?? '07845000007' }}</p>
                    <p><i class="fas fa-envelope"></i> {{ $s['contact_email'] ?? 'info@ardhfalfaw.com' }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> البصرة، العراق</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} شركة أرض الفاو. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- News Modal -->
    <div class="modal" id="newsModal" onclick="if(event.target===this)closeNewsModal()">
        <div class="modal-content news-modal-content">
            <div class="news-modal-image" id="newsModalImage"></div>
            <div class="news-modal-body">
                <div class="news-modal-meta">
                    <span id="newsModalBadge" class="news-badge"></span>
                    <span id="newsModalDate"><i class="fas fa-calendar-alt"></i></span>
                    <span id="newsModalCategory" class="news-cat"></span>
                </div>
                <h2 id="newsModalTitle"></h2>
                <p id="newsModalExcerpt"></p>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <i class="fas fa-check-circle"></i>
            <h3>تم إرسال رسالتك بنجاح!</h3>
            <p>شكراً لتواصلك معنا. سنرد عليك في أقرب وقت ممكن.</p>
            <button class="btn btn-primary" onclick="closeModal()">حسناً</button>
        </div>
    </div>

@endsection
