<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ الطريقة الآمنة: تستخدم `firstOrCreate` باش ما يتكرروش البيانات
        Departement::firstOrCreate(
            ['id' => 1],
            ['nom_dep' => 'Informatique']
        );

        Departement::firstOrCreate(
            ['id' => 2],
            ['nom_dep' => 'Mécanique']
        );

        // ✅ أو الطريقة الأسرع للإدراج المتعدد (بدون التحقق من التكرار)
        /*
        Departement::insert([
            ['id' => 1, 'nom_dep' => 'Informatique'],
            ['id' => 2, 'nom_dep' => 'Mécanique'],
        ]);
        */
    }
}
