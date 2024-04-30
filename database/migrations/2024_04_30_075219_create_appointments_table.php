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
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('healthcare_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->date('appointment_date'); // تاريخ الموعد
            $table->time('appointment_start_time'); // وقت بداية الموعد
            $table->float('appointment_duration');
            $table->string('patient_location'); // موقع المريض
            $table->enum('appointment_status', ['الطلب مرفوض', 'الطلب مقبول', 'الطلب قيد الانتظار']); // حالة الموعد
            $table->unsignedTinyInteger('appointment_rating')->nullable(); // تقييم الموعد
            $table->enum('caregiver_status', ['حضور', 'غياب']); // حالة مقدم الرعاية
            $table->text('complaint', 1000); // يسمح بحتى 1000 حرف//    شكاوي المريض  

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
