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
        Schema::create('healthcare_provider_worktimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('healthcare_provider_id')->constrained()->onDelete('cascade');
            $table->string('day_name');
            $table->integer('work_hours');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['متفرغ', 'محجوز']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_provider_worktimes');
    }
};
