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
        Schema::table('matieres', function (Blueprint $table) {
            // 🔗 ربط المادة بالشعبة
            $table->foreignId('id_filiere')
                  ->constrained('filieres')
                  ->cascadeOnDelete();

            // 🔗 ربط المادة بالسيمستر
            $table->foreignId('id_sem')
                  ->constrained('semestres')
                  ->cascadeOnDelete();

            // 🔗 ربط المادة بالأستاذ المسؤول
            $table->foreignId('id_ens')
                  ->constrained('enseignants')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Keys قبل ما تحيد الأعمدة (ترتيب عكسي)
            $table->dropForeign(['id_ens']);
            $table->dropForeign(['id_sem']);
            $table->dropForeign(['id_filiere']);

            $table->dropColumn(['id_ens', 'id_sem', 'id_filiere']);
        });
    }
};
