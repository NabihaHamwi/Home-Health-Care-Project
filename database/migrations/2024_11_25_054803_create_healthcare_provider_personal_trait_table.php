<?php

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
        Schema::create('healthcare_provider_personal_trait', function (Blueprint $table) {
            $table->unsignedBigInteger(column:'provider_id');
            $table->foreign(columns:'provider_id')->references(columns:'id')->on(table:'healthcare_providers')->onDelete('cascade');
            $table->foreignId('personal_trait_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_provider_personal_trait');
    }
};
