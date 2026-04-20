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
        Schema::table('enseignants', function (Blueprint $table) {
            // 🔗 إضافة العلاقة مع جدول users
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
        Schema::table('enseignants', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Key قبل ما تحيد العمود
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};
