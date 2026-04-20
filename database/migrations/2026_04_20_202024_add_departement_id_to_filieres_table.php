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
        Schema::table('filieres', function (Blueprint $table) {
            // 🔗 إضافة العلاقة مع جدول departements
            $table->foreignId('id_dep')
                  ->constrained('departements')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Key قبل ما تحيد العمود
            $table->dropForeign(['id_dep']);
            $table->dropColumn('id_dep');
        });
    }
};
