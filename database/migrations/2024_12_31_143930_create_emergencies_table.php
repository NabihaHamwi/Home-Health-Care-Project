<?php

use App\Models\HealthcareProvider;
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
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('healthcare_provider_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('healthcare_provider_service_id');
            $table->foreign('healthcare_provider_service_id')->references('id')->on('healthcare_provider_service')->onDelete('cascade');
            $table->string('problem');
            $table->string('location_name');
            $table->float('latitude')->nullable()->check(function ($query) {
                $query->whereBetween('latitude', [33.47, 33.55]);
            });
            $table->float('longitude')->nullable()->check(function ($query) {
                $query->whereBetween('longitude', [36.24, 36.32]);
            });
            $table->time('care_start_time');
            $table->time('care_end_time');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};
