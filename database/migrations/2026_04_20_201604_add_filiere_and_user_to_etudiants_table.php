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
        Schema::table('etudiants', function (Blueprint $table) {
            // 🔗 إضافة المفاتيح الأجنبية (Foreign Keys)
            $table->foreignId('id_filiere')
                  ->constrained('filieres')
                  ->cascadeOnDelete();

            $table->foreignId('id_user')
                  ->constrained('users')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Keys قبل ما تحيد الأعمدة
            $table->dropForeign(['id_filiere']);
            $table->dropForeign(['id_user']);

            $table->dropColumn(['id_filiere', 'id_user']);
        });
    }
};
