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
        Schema::create('healthcare_provider_sub_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('healthcare_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('activity_name');
            $table->enum('activity_type', ['normal', 'measure']);
            $table->date('activity_date'); // يجب التحقق من أن التاريخ مستقبلي عند التخزين
            $table->string('value')->nullable(); // القيمة في حال كان النشاط قياس
            $table->string('comment')->nullable();
            $table->time('activity_execution_time'); // وقت النشاط الذي يجب تنفيذه فيه
            $table->time('activity_time'); // وقت النشاط
            $table->enum('repetition', ['daily', 'weekly', 'once']); // تكرار النشاط
            $table->binary('activity_image')->nullable(); // صورة لإتمام النشاط
            $table->enum('status', ['completed', 'not_completed'])->default('not_completed'); // حالة النشاط
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_provider_sub_service');
    }
};
