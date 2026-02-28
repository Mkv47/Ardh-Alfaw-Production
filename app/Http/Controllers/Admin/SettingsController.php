<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
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
        $request->validate(['logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048']);
        $old = Setting::get('logo');
        if ($old) Storage::disk('public')->delete($old);
        $path = $request->file('logo')->store('images', 'public');
        Setting::set('logo', $path);
        return back()->with('success', 'تم تحديث الشعار بنجاح');
    }
}
