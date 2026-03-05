<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@blooddonation.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => 'Admin Office',
            ]
        );
        
        echo "Admin user created: admin@blooddonation.com / admin123\n";
    }
}
