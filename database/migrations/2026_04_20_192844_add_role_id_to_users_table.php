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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('id_role')
                  ->constrained('roles')
                  ->onDelete('cascade')    // ✅ إلا حذفتي Role، كيتحذفو المستخدمين المرتبطين بيه (اختياري)
                  ->onUpdate('cascade');   // ✅ إلا بدّيتي ID الـ Role، كيتحدّث عند المستخدمين
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ⚠️ ضروري: حيد الـ Foreign Key قبل ما تحيد العمود
            $table->dropForeign(['id_role']);
            $table->dropColumn('id_role');
        });
    }
};
