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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();

            // 🔗 علاقات ضرورية (بدونها الجدول ماكيكونش عملي)
            $table->foreignId('etudiant_id')->constrained('etudiants')->cascadeOnDelete();
            $table->foreignId('seance_id')->constrained('seances')->cascadeOnDelete();
            $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->nullOnDelete();

            $table->boolean('etat')->default(false); // false = غائب، true = مبرر/حاضر
            $table->string('justification', 50)->nullable(); // سبب الغياب أو نوع التبرير
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
