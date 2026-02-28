<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'شركة النقل البحري العامة',  'icon' => 'fas fa-ship',     'sort_order' => 1],
            ['name' => 'شركة نفط البصرة',           'icon' => 'fas fa-oil-well', 'sort_order' => 2],
            ['name' => 'مديرية ماء البصرة',          'icon' => 'fas fa-tint',     'sort_order' => 3],
            ['name' => 'وزارة الصناعة والمعادن',     'icon' => 'fas fa-industry', 'sort_order' => 4],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
