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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('healthcare_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('day_name');//اسماء ايام الاسبوع
            $table->date('appointment_date'); 
            $table->time('appointment_start_time'); 
            $table->time('appointment_duration');
            $table->string('patient_location'); 
            $table->enum('appointment_status', ['الطلب مرفوض', 'الطلب مقبول', 'الطلب قيدالانتظار']); 
            $table->unsignedTinyInteger('appointment_rating')->nullable(); 
            $table->enum('caregiver_status', ['حضور', 'غياب' , '-']); 
            $table->text('complaint', 1000)->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
