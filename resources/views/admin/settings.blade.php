@extends('admin.layout')
@section('page-title', 'الإعدادات العامة')

@section('content')

{{-- Logo Upload --}}
<form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card settings-section" style="margin-bottom:24px">
        <div class="card-header">
            <h2><i class="fas fa-image"></i> شعار الشركة</h2>
        </div>
        <div class="card-body" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
            @php $logoPath = \App\Models\Setting::get('logo'); @endphp
            @if($logoPath)
                <img src="{{ Storage::url($logoPath) }}" alt="الشعار الحالي"
                     style="max-height:90px;max-width:200px;border-radius:8px;border:1px solid #ddd;object-fit:contain;background:#f8f9fa;padding:6px;">
            @else
                <div style="width:120px;height:80px;border-radius:8px;border:2px dashed #ccc;display:flex;align-items:center;justify-content:center;color:#aaa;font-size:2rem">
                    <i class="fas fa-anchor"></i>
                </div>
            @endif
            <div>
                <div class="form-group" style="margin-bottom:0">
                    <label style="display:block;margin-bottom:6px">رفع شعار جديد (PNG/JPG/WebP، بحد أقصى 2 ميجابايت)</label>
                    <input type="file" name="logo" accept="image/*" required>
                </div>
            </div>
        </div>
        <div class="card-footer" style="padding:12px 20px;text-align:left">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> رفع الشعار
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

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> حفظ جميع الإعدادات
        </button>
    </div>
</form>
@endsection
