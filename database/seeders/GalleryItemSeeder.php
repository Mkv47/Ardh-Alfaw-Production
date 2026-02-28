<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GalleryItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['caption' => 'نقل بحري - ميناء أم قصر',      'icon' => 'fas fa-ship',          'key' => 'marine1',  'sort_order' => 1],
            ['caption' => 'نقل معدات ثقيلة',               'icon' => 'fas fa-truck-moving',  'key' => 'truck1',   'sort_order' => 2],
            ['caption' => 'مقاولات كهربائية',              'icon' => 'fas fa-bolt',          'key' => 'elec1',    'sort_order' => 3],
            ['caption' => 'شحن وتفريغ بضائع',             'icon' => 'fas fa-boxes-stacked', 'key' => 'cargo1',   'sort_order' => 4],
            ['caption' => 'توريد مضخات صناعية',           'icon' => 'fas fa-industry',      'key' => 'pump1',    'sort_order' => 5],
            ['caption' => 'عمليات ميناء خور الزبير',      'icon' => 'fas fa-anchor',        'key' => 'port1',    'sort_order' => 6],
        ];

        foreach ($items as $item) {
            GalleryItem::create($item);
        }
    }
}
