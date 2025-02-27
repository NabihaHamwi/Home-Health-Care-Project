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
        Schema::create('activity_details', function (Blueprint $table) {
            $table->id();
            $table->string('sub_activity_name');
            $table->enum('sub_activity_type', ['activity', 'measure', 'medical_appointment', 'medicine']);
            $table->date('start_date')->nullable();
            $table->unsignedInteger('number_of_day')->nullable();
            $table->date('end_date')->nullable();
            $table->time('every_x_hours')->nullable();
            $table->string('user_comment')->nullable();
            // $table->time('sub_activity_time')->nullable();
            $table->unsignedInteger('every_x_day')->nullable();
            $table->unsignedInteger('repeat_count_per_day')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_details');
    }
};
