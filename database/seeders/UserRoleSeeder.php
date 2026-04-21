<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ الطريقة الآمنة: تستخدم `firstOrCreate` باش ما يتكرروش البيانات

        // 👑 Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@app.com'],
            [
                'name' => 'superadmin',
                'id_role' => 1,
                'password' => Hash::make('superadmin'),
                'email_verified_at' => now(),
            ]
        );

        // 🔧 Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'id_role' => 2,
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );

        // 👨‍🏫 Professeur
        User::firstOrCreate(
            ['email' => 'prof@prof.com'],
            [
                'name' => 'prof',
                'id_role' => 3,
                'password' => Hash::make('prof'),
                'email_verified_at' => now(),
            ]
        );

        // 🎓 Étudiant
        User::firstOrCreate(
            ['email' => 'etu@etudiant.com'],
            [
                'name' => 'etudiant',
                'id_role' => 4,
                'password' => Hash::make('etudiant'),
                'email_verified_at' => now(),
            ]
        );
    }
}
