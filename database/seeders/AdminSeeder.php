<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ardhfalfaw.com'],
            [
                'name'     => 'مدير النظام',
                'password' => Hash::make('admin@1234'),
                'is_admin' => true,
            ]
        );
    }
}
