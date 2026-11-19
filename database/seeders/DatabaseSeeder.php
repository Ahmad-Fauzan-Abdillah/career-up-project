<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminName = trim((string) config('careerup.admin.name'));
        $adminEmail = trim((string) config('careerup.admin.email'));
        $adminPassword = (string) config('careerup.admin.password');

        if ($adminName === '' || $adminEmail === '' || trim($adminPassword) === '') {
            throw new RuntimeException(
                'Seeder admin memerlukan ADMIN_NAME, ADMIN_EMAIL, dan ADMIN_PASSWORD yang tidak kosong di file .env.'
            );
        }

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_notification_active' => true,
            ],
        );
    }
}
