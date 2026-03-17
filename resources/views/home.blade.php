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
                <span></span><span></span><span></span>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">الرئيسية</a></li>
                <li><a href="#about">من نحن</a></li>
                @foreach($sectionOrder as $sec)
                    @php $navLabels = ['services'=>'خدماتنا','projects'=>'مشاريعنا','news'=>'الأخبار','team'=>'فريقنا','gallery'=>'معرض الصور','tenders'=>'المناقصات','clients'=>'عملاؤنا','certificates'=>'شهاداتنا']; @endphp
                    @if($sec === 'certificates' && (empty($certificates) || $certificates->isEmpty()))
                        @continue
                    @endif
                    <li><a href="#{{ $sec }}">{{ $navLabels[$sec] ?? $sec }}</a></li>
                @endforeach
                <li><a href="#contact">تواصل معنا</a></li>
            </ul>
        </div>
    </nav>
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
        <div class="hero-scroll"><a href="#about"><i class="fas fa-chevron-down"></i></a></div>
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
                    <p>{!! nl2br(e($s['about_text_1'] ?? 'شركة أرض الفاو للنقل والخدمات البحرية هي شركة عراقية رائدة تأسست عام 2018 في مدينة البصرة.')) !!}</p>
                    <p>{!! nl2br(e($s['about_text_2'] ?? 'نفتخر بشراكاتنا مع كبرى المؤسسات الحكومية والقطاع الخاص.')) !!}</p>
                </div>
                <div class="about-values reveal">
                    <div class="value-card"><i class="fas fa-medal"></i><h3>الاحترافية</h3><p>نعمل بأعلى معايير الاحترافية في جميع خدماتنا</p></div>
                    <div class="value-card"><i class="fas fa-shield-alt"></i><h3>السلامة</h3><p>السلامة أولاً في جميع عملياتنا</p></div>
                    <div class="value-card"><i class="fas fa-gem"></i><h3>الجودة</h3><p>نلتزم بأعلى معايير الجودة العالمية</p></div>
                    <div class="value-card"><i class="fas fa-handshake"></i><h3>المصداقية</h3><p>الشفافية والمصداقية في التعامل</p></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Dynamic sections in saved order --}}
    @foreach($sectionOrder as $sec)
        @includeIf('partials.sections.' . $sec)
    @endforeach

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
                    <div class="contact-card"><i class="fas fa-map-marker-alt"></i><div><h4>المقر الرئيسي</h4><p>{{ $s['contact_address_main'] ?? 'البصرة - البراضعية - شارع سيد أمين' }}</p></div></div>
                    <div class="contact-card"><i class="fas fa-building"></i><div><h4>الفرع</h4><p>{{ $s['contact_address_branch'] ?? 'البصرة - الجزأر - مقابل مركز فينيسيا' }}</p></div></div>
                    <div class="contact-card"><i class="fas fa-phone"></i><div><h4>الهاتف</h4><p dir="ltr">{{ $s['contact_phone_1'] ?? '07845000007' }}</p><p dir="ltr">{{ $s['contact_phone_2'] ?? '07724300004' }}</p></div></div>
                    <div class="contact-card"><i class="fas fa-envelope"></i><div><h4>البريد الإلكتروني</h4><p>{{ $s['contact_email'] ?? 'info@ardhfalfaw.com' }}</p></div></div>
                    <a href="https://wa.me/{{ $s['contact_whatsapp'] ?? '9647845000007' }}" target="_blank" class="whatsapp-btn">
                        <i class="fab fa-whatsapp"></i> تواصل عبر واتساب
                    </a>
                </div>
                <form class="contact-form reveal" id="contactForm">
                    @csrf
                    <div class="form-group"><label for="name">الاسم الكامل *</label><input type="text" id="name" name="name" required></div>
                    <div class="form-row">
                        <div class="form-group"><label for="email">البريد الإلكتروني *</label><input type="email" id="email" name="email" required></div>
                        <div class="form-group"><label for="phone">رقم الهاتف</label><input type="tel" id="phone" name="phone"></div>
                    </div>
                    <div class="form-group"><label for="company">اسم الشركة</label><input type="text" id="company" name="company"></div>
                    <div class="form-group"><label for="subject">الموضوع *</label><input type="text" id="subject" name="subject" required></div>
                    <div class="form-group"><label for="message">الرسالة *</label><textarea id="message" name="message" rows="5" required></textarea></div>
                    <button type="submit" class="btn btn-primary btn-full"><i class="fas fa-paper-plane"></i> إرسال الرسالة</button>
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
                        @if(!empty($s['logo']))<img src="{{ Storage::url($s['logo']) }}" alt="أرض الفاو" class="logo-img">@else<i class="fas fa-anchor"></i>@endif
                        <span>أرض الفاو</span>
                    </a>
                    <p>{{ $s['footer_description'] ?? 'شركة عراقية رائدة في مجال النقل والخدمات البحرية، تأسست عام 2018 في البصرة.' }}</p>
                </div>
                <div class="footer-links"><h4>روابط سريعة</h4><ul>
                    <li><a href="#home">الرئيسية</a></li><li><a href="#about">من نحن</a></li>
                    <li><a href="#services">خدماتنا</a></li><li><a href="#projects">مشاريعنا</a></li>
                    <li><a href="#news">الأخبار</a></li><li><a href="#team">فريقنا</a></li>
                    <li><a href="#tenders">المناقصات</a></li><li><a href="#contact">تواصل معنا</a></li>
                </ul></div>
                <div class="footer-services"><h4>خدماتنا</h4><ul>
                    <li>النقل البحري</li><li>النقل البري</li><li>الشحن والتفريغ</li>
                    <li>الاستيراد والتصدير</li><li>المقاولات الكهربائية</li>
                </ul></div>
                <div class="footer-contact"><h4>تواصل معنا</h4>
                    <p><i class="fas fa-phone"></i> {{ $s['contact_phone_1'] ?? '07845000007' }}</p>
                    <p><i class="fas fa-envelope"></i> {{ $s['contact_email'] ?? 'info@ardhfalfaw.com' }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> البصرة، العراق</p>
                </div>
            </div>
            <div class="footer-bottom"><p>&copy; {{ date('Y') }} شركة أرض الفاو. جميع الحقوق محفوظة.</p></div>
        </div>
    </footer>

    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

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

    <!-- PDF Viewer Modal (certificates) -->
    <div id="certModal" class="cert-modal" onclick="closeCertModal(event)">
        <div class="cert-modal-box">
            <div class="cert-modal-header">
                <span id="certModalTitle"></span>
                <button class="cert-modal-close" onclick="closeCertModal()">&times;</button>
            </div>
            <iframe id="certModalFrame" src="" allowfullscreen></iframe>
        </div>
    </div>

@endsection

<script>
function openCertModal(url, title) {
    document.getElementById('certModalTitle').textContent = title;
    document.getElementById('certModalFrame').src = url;
    document.getElementById('certModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeCertModal(e) {
    if (e && e.target !== document.getElementById('certModal') && !e.target.classList.contains('cert-modal-close')) return;
    document.getElementById('certModal').classList.remove('open');
    document.getElementById('certModalFrame').src = '';
    if (!document.getElementById('certsAllModal')?.classList.contains('open')) {
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeCertModal({});
        const allModal = document.getElementById('certsAllModal');
        if (allModal) { allModal.classList.remove('open'); document.body.style.overflow = ''; }
    }
});
</script>
