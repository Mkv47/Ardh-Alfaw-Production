<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title'       => 'خدمات النقل البحري',
                'description' => 'نقدم خدمات النقل البحري المتكاملة عبر موانئ البصرة وأم قصر، مع أسطول حديث وطاقم متخصص.',
                'icon'        => 'fas fa-ship',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'خدمات النقل البري',
                'description' => 'أسطول متنوع من الشاحنات والمعدات الثقيلة لنقل البضائع داخل العراق وإلى دول الجوار.',
                'icon'        => 'fas fa-truck',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'الشحن والتفريغ',
                'description' => 'خدمات شحن وتفريغ البضائع في الموانئ مع معدات متطورة وفرق عمل متخصصة.',
                'icon'        => 'fas fa-boxes-stacked',
                'sort_order'  => 3,
            ],
            [
                'title'       => 'الاستيراد والتصدير',
                'description' => 'نسهل عمليات الاستيراد والتصدير مع التخليص الجمركي وإدارة سلسلة التوريد.',
                'icon'        => 'fas fa-globe',
                'sort_order'  => 4,
            ],
            [
                'title'       => 'المقاولات الكهربائية',
                'description' => 'تنفيذ المشاريع الكهربائية للقطاعين الحكومي والخاص بأعلى المعايير.',
                'icon'        => 'fas fa-bolt',
                'sort_order'  => 5,
            ],
            [
                'title'       => 'تجهيز المعدات',
                'description' => 'توريد وتجهيز المعدات الصناعية والبحرية من أفضل المصادر العالمية.',
                'icon'        => 'fas fa-cogs',
                'sort_order'  => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
