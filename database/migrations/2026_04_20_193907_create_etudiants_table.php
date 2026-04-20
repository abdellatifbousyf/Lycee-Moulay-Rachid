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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('cne', 20)->unique()->index(); // ✅ CNE ضروري يكون فريد ومسجل
            $table->string('nom_etu');
            $table->string('prenom_etu');
            $table->string('phone_etu')->nullable();      // ✅ الهاتف كيكون string باش يقبل الأصفار و الصيغ الدولية
            $table->timestamps();

            // 💡 اختياري: إلا بغيتي "حذف ناعم" (Soft Deletes) للتلاميذ
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
