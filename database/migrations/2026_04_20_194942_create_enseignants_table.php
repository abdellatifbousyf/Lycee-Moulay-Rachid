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
        Schema::create('enseignants', function (Blueprint $table) {
            $table->id();
            $table->string('nom_ens');
            $table->string('prenom_ens');
            $table->string('phone_ens')->nullable();      // ✅ الهاتف خاصو يكون string باش يقبل 06/07 و الصيغ الدولية
            $table->text('adresse_ens')->nullable();      // ✅ العنوان كيكون nullable باش ما يعطيش خطأ فاش يكون فارغ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enseignants');
    }
};
