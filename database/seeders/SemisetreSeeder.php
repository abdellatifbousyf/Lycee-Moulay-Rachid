<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semestre;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ الطريقة الآمنة: تستخدم `firstOrCreate` باش ما يتكرروش البيانات
        Semestre::firstOrCreate(
            ['id' => 1],
            ['nom_sem' => 'S1']
        );

        Semestre::firstOrCreate(
            ['id' => 2],
            ['nom_sem' => 'S2']
        );



        // ✅ أو الطريقة الأسرع والأكثر نظافة للإدراج المتعدد

        Semestre::upsert(
            [
                ['id' => 1, 'nom_sem' => 'S1'],
                ['id' => 2, 'nom_sem' => 'S2'],

            ],
            ['id'], // Unique key للتحقق
            ['nom_sem'] // الحقول اللي كيتحدّثو يلا لقا الـ ID
        );

    }
}
