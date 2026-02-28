<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            ProjectSeeder::class,
            NewsSeeder::class,
            TeamMemberSeeder::class,
            TenderSeeder::class,
            ClientSeeder::class,
            GalleryItemSeeder::class,
            SettingsSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
