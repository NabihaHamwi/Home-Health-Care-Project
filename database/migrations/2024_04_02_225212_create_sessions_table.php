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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id('sessionId');
            $table->time('time', $precision = 0);
            $table->integer('temeprature');
            $table->integer('pulseRate');
            $table->integer('diastolicPressure');
            $table->float('systolicPressure', 8, 2);
            $table->float('oxygenLevel', 8, 2);
            // $table->timestamps();
            $table->integer('sugerLevel');
            $table->string('inflammationSigns', 100);
            $table->string('consciousnessLevel', 100);
            $table->string('urineReleased', 100);
            $table->string('givenserum', 100);
            $table->string('givenMedicine', 100);
            $table->string('food', 100);
            $table->string('cleanLiness', 100);
            $table->string('medicine', 100);
            $table->string('painLevel');
            $table->string('exercises');
            $table->string('treatmentProgress');
            $table->string('observation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
