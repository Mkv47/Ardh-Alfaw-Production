@if(isset($certificates) && $certificates->isNotEmpty())
<section class="section certs-section" id="certificates">
    <div class="container">
        <div class="certs-card">
            <div class="certs-card-header">
                <i class="fas fa-certificate"></i>
                <h2>شهاداتنا وشهادات الجودة</h2>
                <p>اعتمادات دولية تؤكد التزامنا بأعلى معايير الجودة</p>
            </div>
            <div class="certs-card-body">
                <div class="certs-grid">
                    @foreach($certificates->take(4) as $cert)
                    @include('partials.cert-card', ['cert' => $cert])
                    @endforeach
                </div>

                @if($certificates->count() > 4)
                <div class="certs-more-wrap">
                    <button class="certs-more-btn" onclick="document.getElementById('certsAllModal').classList.add('open'); document.body.style.overflow='hidden'">
                        <i class="fas fa-th-large"></i>
                        عرض جميع الشهادات ({{ $certificates->count() }})
                    </button>
                </div>
                <div id="certsAllModal" class="certs-all-modal" onclick="if(event.target===this){this.classList.remove('open');document.body.style.overflow=''}">
                    <div class="certs-all-box">
                        <div class="certs-all-header">
                            <h3><i class="fas fa-certificate" style="color:var(--teal);margin-left:8px"></i> جميع الشهادات والاعتمادات</h3>
                            <button class="certs-all-close" onclick="document.getElementById('certsAllModal').classList.remove('open');document.body.style.overflow=''">&times;</button>
                        </div>
                        <div class="certs-all-body">
                            @foreach($certificates as $cert)
                            @include('partials.cert-card', ['cert' => $cert])
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
