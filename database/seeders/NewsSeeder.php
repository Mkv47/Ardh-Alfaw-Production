<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $news = [
            [
                'title'        => 'توسعة أسطولنا البحري بإضافة وحدات نقل جديدة',
                'excerpt'      => 'تفخر شركة أرض الفاو بالإعلان عن توسعة أسطولها البحري بإضافة وحدات نقل حديثة لتعزيز قدراتها في موانئ البصرة وأم قصر.',
                'icon'         => 'fas fa-ship',
                'badge'        => 'جديد',
                'category'     => 'خدمات بحرية',
                'published_at' => '2025-01-15 00:00:00',
                'sort_order'   => 1,
            ],
            [
                'title'        => 'توقيع عقد جديد مع شركة نفط البصرة',
                'excerpt'      => 'أبرمت شركة أرض الفاو عقداً جديداً مع شركة نفط البصرة لتقديم خدمات نقل المعدات الثقيلة لمدة عامين قادمين.',
                'icon'         => 'fas fa-handshake',
                'badge'        => 'شراكة',
                'category'     => 'أعمال',
                'published_at' => '2024-12-10 00:00:00',
                'sort_order'   => 2,
            ],
            [
                'title'        => 'إتمام مشروع توريد المضخات لمديرية ماء البصرة',
                'excerpt'      => 'أنهت الشركة بنجاح مشروع توريد وتركيب منظومة مضخات المياه لمديرية ماء البصرة في الموعد المحدد.',
                'icon'         => 'fas fa-trophy',
                'badge'        => 'إنجاز',
                'category'     => 'مشاريع',
                'published_at' => '2024-11-05 00:00:00',
                'sort_order'   => 3,
            ],
        ];

        foreach ($news as $item) {
            News::create($item);
        }
    }
}
