<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title'          => 'نقل معدات بحرية - ميناء أم قصر',
                'description'    => 'نقل وتركيب معدات بحرية لصالح شركة النقل البحري العامة',
                'icon'           => 'fas fa-anchor',
                'category_key'   => 'marine',
                'category_label' => 'بحري',
                'client'         => 'النقل البحري العام',
                'year'           => '2023',
                'sort_order'     => 1,
            ],
            [
                'title'          => 'نقل معدات ثقيلة - شركة نفط البصرة',
                'description'    => 'نقل معدات حفر ومضخات لحقول نفط البصرة',
                'icon'           => 'fas fa-truck-moving',
                'category_key'   => 'transport',
                'category_label' => 'نقل',
                'client'         => 'شركة نفط البصرة',
                'year'           => '2024',
                'sort_order'     => 2,
            ],
            [
                'title'          => 'توريد مضخات - ماء البصرة',
                'description'    => 'توريد وتركيب مضخات مياه لمشاريع مديرية ماء البصرة',
                'icon'           => 'fas fa-industry',
                'category_key'   => 'supply',
                'category_label' => 'توريد',
                'client'         => 'مديرية ماء البصرة',
                'year'           => '2023',
                'sort_order'     => 3,
            ],
            [
                'title'          => 'تمديدات كهربائية - وزارة الصناعة',
                'description'    => 'تنفيذ أعمال التمديدات الكهربائية لمنشآت صناعية',
                'icon'           => 'fas fa-bolt',
                'category_key'   => 'contracts',
                'category_label' => 'مقاولات',
                'client'         => 'وزارة الصناعة',
                'year'           => '2024',
                'sort_order'     => 4,
            ],
            [
                'title'          => 'خدمات شحن - ميناء خور الزبير',
                'description'    => 'عمليات شحن وتفريغ لبضائع مستوردة',
                'icon'           => 'fas fa-ship',
                'category_key'   => 'marine',
                'category_label' => 'بحري',
                'client'         => 'القطاع الخاص',
                'year'           => '2024',
                'sort_order'     => 5,
            ],
            [
                'title'          => 'نقل مواد بناء - مشاريع إعمار',
                'description'    => 'نقل مواد بناء لمشاريع إعادة الإعمار في المنطقة',
                'icon'           => 'fas fa-truck',
                'category_key'   => 'transport',
                'category_label' => 'نقل',
                'client'         => 'شركات مقاولات',
                'year'           => '2023',
                'sort_order'     => 6,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
