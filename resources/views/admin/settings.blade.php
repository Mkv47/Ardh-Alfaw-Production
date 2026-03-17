@extends('admin.layout')
@section('page-title', 'الإعدادات العامة')

@section('content')

{{-- Navbar Logo Upload --}}
<form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-image"></i> شعار الشريط العلوي (Navbar)</h2>
        </div>
        <div class="card-body">
            @include('admin.partials.logo-cropper', [
                'currentImage' => \App\Models\Setting::get('logo'),
                'inputId'      => 'navLogo',
                'hiddenName'   => 'logo_cropped',
            ])
        </div>
        <div class="card-footer" style="padding:12px 20px;text-align:left">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ
            </button>
        </div>
    </div>
</form>

{{-- Hero Logo Upload --}}
<form action="{{ route('admin.settings.hero-logo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-star"></i> شعار الصفحة الرئيسية (Hero)</h2>
        </div>
        <div class="card-body">
            @include('admin.partials.logo-cropper', [
                'currentImage' => \App\Models\Setting::get('hero_logo'),
                'inputId'      => 'heroLogo',
                'hiddenName'   => 'hero_logo_cropped',
            ])
            <div class="form-group" style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                @php $heroLogoSize = \App\Models\Setting::get('hero_logo_size') ?? 600; @endphp
                <label style="display:block;margin-bottom:6px">
                    حجم العلامة المائية الدائرية: <strong><span id="heroLogoSizeVal">{{ $heroLogoSize }}</span> px</strong>
                </label>
                <input type="range" name="hero_logo_size" min="100" max="1200" value="{{ $heroLogoSize }}"
                       oninput="document.getElementById('heroLogoSizeVal').textContent=this.value"
                       style="width:100%;accent-color:var(--teal-accent)">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#888;margin-top:2px">
                    <span>100px</span><span>1200px</span>
                </div>
            </div>
            <div class="form-group" style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border)">
                @php $heroLogoOpacity = \App\Models\Setting::get('hero_logo_opacity') ?? 15; @endphp
                <label style="display:block;margin-bottom:6px">
                    شفافية العلامة المائية (Opacity): <strong><span id="heroLogoOpacityVal">{{ $heroLogoOpacity }}</span>%</strong>
                </label>
                <input type="range" name="hero_logo_opacity" min="0" max="100" value="{{ $heroLogoOpacity }}"
                       oninput="document.getElementById('heroLogoOpacityVal').textContent=this.value"
                       style="width:100%;accent-color:var(--teal-accent)">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#888;margin-top:2px">
                    <span>0%</span><span>100%</span>
                </div>
            </div>
        </div>
        <div class="card-footer" style="padding:12px 20px;text-align:left">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> حفظ
            </button>
        </div>
    </div>
</form>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Hero --}}
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-home"></i> قسم الرئيسية (Hero)</h2>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>اسم الشركة (العنوان الرئيسي)</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] }}">
                </div>
                <div class="form-group">
                    <label>الشعار / التخصص</label>
                    <input type="text" name="hero_subtitle" value="{{ $settings['hero_subtitle'] }}">
                </div>
                <div class="form-group">
                    <label>الموقع الجغرافي</label>
                    <input type="text" name="hero_location" value="{{ $settings['hero_location'] }}">
                </div>
                <div class="form-group">
                    <label>سنوات الخبرة (رقم)</label>
                    <input type="number" name="hero_stat_years" value="{{ $settings['hero_stat_years'] }}">
                </div>
                <div class="form-group">
                    <label>عدد مجالات العمل (رقم)</label>
                    <input type="number" name="hero_stat_fields" value="{{ $settings['hero_stat_fields'] }}">
                </div>
                <div class="form-group">
                    <label>عدد العملاء الحكوميين (رقم)</label>
                    <input type="number" name="hero_stat_clients" value="{{ $settings['hero_stat_clients'] }}">
                </div>
            </div>
        </div>
    </div>

    {{-- About --}}
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-info-circle"></i> قسم من نحن</h2>
        </div>
        <div class="card-body">
            <div class="form-grid full">
                <div class="form-group">
                    <label>الفقرة الأولى</label>
                    <textarea name="about_text_1" rows="4">{{ $settings['about_text_1'] }}</textarea>
                </div>
                <div class="form-group">
                    <label>الفقرة الثانية</label>
                    <textarea name="about_text_2" rows="3">{{ $settings['about_text_2'] }}</textarea>
                </div>
                <div class="form-group">
                    <label>سنة التأسيس</label>
                    <input type="text" name="founded_year" value="{{ $settings['founded_year'] }}" style="max-width:150px">
                </div>
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-phone"></i> معلومات التواصل</h2>
        </div>
        <div class="card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>عنوان المقر الرئيسي</label>
                    <input type="text" name="contact_address_main" value="{{ $settings['contact_address_main'] }}">
                </div>
                <div class="form-group">
                    <label>عنوان الفرع</label>
                    <input type="text" name="contact_address_branch" value="{{ $settings['contact_address_branch'] }}">
                </div>
                <div class="form-group">
                    <label>رقم الهاتف الأول</label>
                    <input type="text" name="contact_phone_1" value="{{ $settings['contact_phone_1'] }}">
                </div>
                <div class="form-group">
                    <label>رقم الهاتف الثاني</label>
                    <input type="text" name="contact_phone_2" value="{{ $settings['contact_phone_2'] }}">
                </div>
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}">
                </div>
                <div class="form-group">
                    <label>رقم واتساب (مع رمز الدولة، بدون +)</label>
                    <input type="text" name="contact_whatsapp" value="{{ $settings['contact_whatsapp'] }}" placeholder="9647845000007">
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-shoe-prints"></i> الفوتر</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>وصف الشركة في الفوتر</label>
                <textarea name="footer_description" rows="3">{{ $settings['footer_description'] }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section Order --}}
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-sort"></i> ترتيب الأقسام</h2>
        </div>
        <div class="card-body">
            <p style="color:var(--gray);font-size:.875rem;margin-bottom:16px">اسحب الأقسام لتغيير ترتيب ظهورها على الصفحة الرئيسية. (الرئيسية، من نحن، وتواصل معنا ثابتة)</p>
            @php
                $allSections = ['services'=>'خدماتنا','projects'=>'مشاريعنا','news'=>'الأخبار','team'=>'فريقنا','gallery'=>'معرض الصور','tenders'=>'المناقصات','clients'=>'عملاؤنا','certificates'=>'الشهادات'];
                $savedOrder  = json_decode(\App\Models\Setting::get('section_order') ?? '[]', true) ?: array_keys($allSections);
                $mergedOrder = array_unique([...$savedOrder, ...array_keys($allSections)]);
            @endphp
            <input type="hidden" name="section_order" id="sectionOrderInput"
                   value="{{ \App\Models\Setting::get('section_order') ?? json_encode(array_keys($allSections)) }}">
            <ul id="sectionSortable" style="list-style:none;padding:0;margin:0;max-width:420px">
                @foreach($mergedOrder as $key)
                @if(isset($allSections[$key]))
                <li data-key="{{ $key }}" style="display:flex;align-items:center;gap:12px;padding:10px 14px;margin-bottom:8px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;cursor:grab;user-select:none">
                    <i class="fas fa-grip-vertical" style="color:#94a3b8;cursor:grab"></i>
                    <span style="font-weight:600;color:var(--primary-navy)">{{ $allSections[$key] }}</span>
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> حفظ جميع الإعدادات
        </button>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// Section order drag-and-drop
Sortable.create(document.getElementById('sectionSortable'), {
    animation: 150,
    ghostClass: 'sort-ghost',
    onEnd: function() {
        const order = [...document.querySelectorAll('#sectionSortable li')].map(li => li.dataset.key);
        document.getElementById('sectionOrderInput').value = JSON.stringify(order);
    }
});
</script>
<style>
.sort-ghost { opacity:.4; background:#e0f2fe !important; border-color:#7dd3fc !important; }
#sectionSortable li:hover { border-color:var(--teal); }
#sectionSortable li { transition: border-color .15s; }
</style>
@endsection
