<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ الطريقة الآمنة: تستخدم `firstOrCreate` باش ما يتكرروش البيانات
        Role::firstOrCreate(
            ['id' => 1],
            ['type' => 'superadmin']
        );

        Role::firstOrCreate(
            ['id' => 2],
            ['type' => 'admin']
        );

        Role::firstOrCreate(
            ['id' => 3],
            ['type' => 'prof']
        );

        Role::firstOrCreate(
            ['id' => 4],
            ['type' => 'etudiant']
        );

        // ✅ أو الطريقة الأسرع للإدراج المتعدد (بدون التحقق من التكرار)
        /*
        Role::insert([
            ['id' => 1, 'type' => 'superadmin'],
            ['id' => 2, 'type' => 'admin'],
            ['id' => 3, 'type' => 'prof'],
            ['id' => 4, 'type' => 'etudiant'],
        ]);
        */
    }
}
