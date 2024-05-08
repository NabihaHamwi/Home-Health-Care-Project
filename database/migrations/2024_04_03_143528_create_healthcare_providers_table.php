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
        Schema::create('healthcare_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('gender' , ['أنثى', 'ذكر'] ); 
            $table->integer('age');
            $table->enum('relationship_status' , ['أعزب','متزوج','أرمل','مطلق' ,'-' ]);
            $table->integer('experience');
            $table->binary('personal_image')->nullable();
            $table->string('physical_strength'); //القوة البدنية
            $table->float('min_working_hours_per_day'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_providers');
    }
};
