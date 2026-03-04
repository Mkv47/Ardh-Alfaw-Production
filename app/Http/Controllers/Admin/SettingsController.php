<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesCroppedImage;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    use SavesCroppedImage;

    public function index()
    {
        $keys = [
            'hero_title','hero_subtitle','hero_location',
            'hero_stat_years','hero_stat_fields','hero_stat_clients',
            'about_text_1','about_text_2',
            'contact_address_main','contact_address_branch',
            'contact_phone_1','contact_phone_2',
            'contact_email','contact_whatsapp',
            'footer_description','founded_year',
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::get($key);
        }

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');
        Setting::setMany($data);

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo_cropped' => 'nullable|string',
            'logo_size'    => 'nullable|integer|min:24|max:200',
        ]);
        if ($request->filled('logo_cropped')) {
            $old = Setting::get('logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('logo', $this->saveCroppedImage($request->input('logo_cropped'), 'images'));
        }
        if ($request->filled('logo_size')) {
            Setting::set('logo_size', $request->input('logo_size'));
        }
        return back()->with('success', 'تم تحديث شعار الشريط العلوي بنجاح');
    }

    public function updateHeroLogo(Request $request)
    {
        $request->validate([
            'hero_logo_cropped' => 'nullable|string',
            'hero_logo_size'    => 'nullable|integer|min:100|max:1200',
        ]);
        if ($request->filled('hero_logo_cropped')) {
            $old = Setting::get('hero_logo');
            if ($old) Storage::disk('public')->delete($old);
            Setting::set('hero_logo', $this->saveCroppedImage($request->input('hero_logo_cropped'), 'images'));
        }
        if ($request->filled('hero_logo_size')) {
            Setting::set('hero_logo_size', $request->input('hero_logo_size'));
        }
        return back()->with('success', 'تم تحديث شعار الصفحة الرئيسية بنجاح');
    }
}
