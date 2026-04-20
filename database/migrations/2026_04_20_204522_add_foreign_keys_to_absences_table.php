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
        Schema::table('absences', function (Blueprint $table) {
            // 🔗 ربط الغياب بالحصة (Séance)
            $table->foreignId('id_sea')
                  ->constrained('seances')
                  ->cascadeOnDelete();

            // 🔗 ربط الغياب بالتلميذ (Étudiant)
            $table->foreignId('id_etu')
                  ->constrained('etudiants')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absences', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Keys قبل ما تحيد الأعمدة (ترتيب عكسي)
            $table->dropForeign(['id_etu']);
            $table->dropForeign(['id_sea']);

            $table->dropColumn(['id_etu', 'id_sea']);
        });
    }
};
