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
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->date('date');                      // ✅ يمكن تغييرها لـ date_seance لتجنب اللبس مع دوال SQL
            $table->string('type', 50)->default('cours'); // ✅ طول محدد + قيمة افتراضية
            $table->boolean('active')->default(true);     // ✅ القيمة الافتراضية ضرورية
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
