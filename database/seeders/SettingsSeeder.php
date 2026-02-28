<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero
            'hero_title'        => 'شركة أرض الفاو',
            'hero_subtitle'     => 'للنقل والخدمات البحرية',
            'hero_location'     => 'البصرة، العراق',
            'hero_stat_years'   => '7',
            'hero_stat_fields'  => '6',
            'hero_stat_clients' => '4',

            // About
            'about_text_1' => 'شركة أرض الفاو للنقل والخدمات البحرية هي شركة عراقية رائدة تأسست عام 2018 في مدينة البصرة. نتخصص في تقديم حلول متكاملة في مجالات النقل البحري والبري، خدمات الشحن والتفريغ، الاستيراد والتصدير، المقاولات الكهربائية، وتجهيز المعدات.',
            'about_text_2' => 'نفتخر بشراكاتنا مع كبرى المؤسسات الحكومية والقطاع الخاص، ونسعى دائماً لتقديم أعلى معايير الجودة والاحترافية في جميع خدماتنا.',

            // Contact
            'contact_address_main'   => 'البصرة - البراضعية - شارع سيد أمين',
            'contact_address_branch' => 'البصرة - الجزأر - مقابل مركز فينيسيا',
            'contact_phone_1'        => '07845000007',
            'contact_phone_2'        => '07724300004',
            'contact_email'          => 'info@ardhfalfaw.com',
            'contact_whatsapp'       => '9647845000007',

            // Footer
            'footer_description' => 'شركة عراقية رائدة في مجال النقل والخدمات البحرية، تأسست عام 2018 في البصرة.',
            'founded_year'       => '2018',
        ];

        Setting::setMany($settings);
    }
}
