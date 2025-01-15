<?php

use App\Models\HealthcareProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->float('latitude')->check(function ($query) {
                $query->whereBetween('latitude', [33.47, 33.55]);
            });
            $table->float('longitude')->check(function ($query) {
                $query->whereBetween('longitude', [36.24, 36.32]);
            });
            $table->timestamp('care_appointment_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->time('care_start_time')->nullable();
            $table->time('care_end_time')->nullable();
            $table->enum('appointment_status', ['scheduled', 'in_progress', 'completed'])->default('scheduled');
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
