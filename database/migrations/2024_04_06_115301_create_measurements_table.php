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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id('measurments_id');
            $table->unsignedBigInteger('session_id');
            $table->foreign('session_id')->references('session_id')->on('sessions');

            $table->unsignedBigInteger('activity_id');
            $table->foreign('activity_id')->references('activity_id')->on('activities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
