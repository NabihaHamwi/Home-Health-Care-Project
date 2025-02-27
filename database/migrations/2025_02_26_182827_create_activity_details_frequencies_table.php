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
        Schema::create('activity_details_frequencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_detail_id')->constrained('activity_details')->onDelete('cascade');
            $table->foreignId('activity_appointment_id')->constrained('activity_appointment')->onDelete('cascade'); 
            //$table->string('day_name')->nullable();
            $table->time('start_time')->nullable();
            // $table->text('user_comment')->nullable();
            $table->time('sub_activity_execution_time')->nullable();
            $table->string('value')->nullable();
            $table->string('provider_comment')->nullable();
            $table->binary('sub_activity_image')->nullable();
            $table->enum('status', ['completed', 'not_completed'])->default('not_completed'); // حالة النشاط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_details_frequencies');
    }
};
