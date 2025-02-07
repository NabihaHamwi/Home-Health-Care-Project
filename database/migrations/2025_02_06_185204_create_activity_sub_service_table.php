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
        Schema::create('activity_sub_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_service_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('sub_activity_name');
            $table->enum('sub_activity_type', ['activity', 'measure', 'medical_appointment', 'medicine']);
            $table->date('sub_activity_date')->nullable(); // يجب التحقق من أن التاريخ مستقبلي عند التخزين
            $table->string('value')->nullable(); // القيمة في حال كان النشاط قياس
            $table->string('user_comment')->nullable();
            $table->string('provider_comment')->nullable();
            $table->time('sub_activity_execution_time')->nullable(); // وقت النشاط الذي يجب تنفيذه فيه
            $table->time('sub_activity_time')->nullable(); // وقت النشاط
            $table->enum('repetition', ['once', 'daily', 'weekly'])->nullable(); // تكرار النشاط
            $table->integer('every_x_day')->nullable();
            $table->binary('sub_activity_image')->nullable(); // صورة لإتمام النشاط
            $table->enum('status', ['completed', 'not_completed'])->default('not_completed'); // حالة النشاط

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_sub_service');
    }
};
