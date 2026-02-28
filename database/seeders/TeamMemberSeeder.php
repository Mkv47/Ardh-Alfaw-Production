<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name'       => 'أحمد الشهمان',
                'role'       => 'الرئيس التنفيذي (CEO)',
                'bio'        => 'قائد الشركة ومؤسسها، يمتلك خبرة واسعة في مجال النقل والخدمات اللوجستية في جنوب العراق.',
                'icon'       => 'fas fa-user-tie',
                'whatsapp'   => '9647845000007',
                'sort_order' => 1,
            ],
            [
                'name'       => 'مدير العمليات البحرية',
                'role'       => 'مدير العمليات',
                'bio'        => 'متخصص في إدارة العمليات البحرية وتنسيق شحن البضائع عبر موانئ البصرة.',
                'icon'       => 'fas fa-user-cog',
                'whatsapp'   => null,
                'sort_order' => 2,
            ],
            [
                'name'       => 'مدير المشاريع والمقاولات',
                'role'       => 'مدير المشاريع',
                'bio'        => 'خبرة في إدارة مشاريع المقاولات الكهربائية والتوريدات الصناعية.',
                'icon'       => 'fas fa-user-shield',
                'whatsapp'   => null,
                'sort_order' => 3,
            ],
            [
                'name'       => 'مسؤول التخليص الجمركي',
                'role'       => 'مختص جمارك ولوجستيات',
                'bio'        => 'متمرس في إجراءات الاستيراد والتصدير والتخليص الجمركي في الموانئ العراقية.',
                'icon'       => 'fas fa-user-graduate',
                'whatsapp'   => null,
                'sort_order' => 4,
            ],
        ];

        foreach ($members as $member) {
            TeamMember::create($member);
        }
    }
}
