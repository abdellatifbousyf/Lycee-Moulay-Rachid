<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            // 🔗 ربط الحصة بالأستاذ
            $table->foreignId('id_ens')
                  ->constrained('enseignants')
                  ->cascadeOnDelete();

            // 🔗 ربط الحصة بالمادة
            $table->foreignId('id_mat')
                  ->constrained('matieres')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Keys قبل ما تحيد الأعمدة (ترتيب عكسي)
            $table->dropForeign(['id_mat']);
            $table->dropForeign(['id_ens']);

            $table->dropColumn(['id_mat', 'id_ens']);
        });
    }
};
