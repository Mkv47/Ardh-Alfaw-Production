<?php

namespace Database\Seeders;

use App\Models\Tender;
use Illuminate\Database\Seeder;

class TenderSeeder extends Seeder
{
    public function run(): void
    {
        $tenders = [
            [
                'title'       => 'مناقصة توريد معدات رافعات ميناء أم قصر',
                'description' => 'تدعو شركة أرض الفاو الشركات المؤهلة لتقديم عروضها لتوريد معدات رافعات بحرية متخصصة.',
                'type'        => 'توريد معدات',
                'status'      => 'open',
                'deadline'    => '2025-03-01',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'عطاء نقل معدات حفر لحقول نفط البصرة',
                'description' => 'مطلوب شركات نقل مرخصة لتقديم عروض نقل معدات حفر ثقيلة من الميناء إلى مواقع الحفر.',
                'type'        => 'نقل وخدمات',
                'status'      => 'open',
                'deadline'    => '2025-03-15',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'مناقصة تمديدات كهربائية - منشآت وزارة الصناعة',
                'description' => 'تم إغلاق المناقصة وتم الترسية على الشركة المؤهلة. شكراً للمشاركين جميعاً.',
                'type'        => 'مقاولات كهربائية',
                'status'      => 'closed',
                'deadline'    => '2025-01-10',
                'sort_order'  => 3,
            ],
        ];

        foreach ($tenders as $tender) {
            Tender::create($tender);
        }
    }
}
