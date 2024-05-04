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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->enum('gender' , ['أنثى', 'ذكر'] ); 
            $table->date('birth_date');
            $table->enum('relationship_status' , ['أعزب','متزوج','أرمل','مطلق' ,'-' ]);
            $table->string('address');
            $table->string('phone_number');
            $table->decimal('weight', 5, 2); // إضافة وزن المريض
            $table->integer('height'); // إضافة طول المريض
            $table->text('previous_diseases_surgeries')->nullable(); // جراحات  الامراض السابقة
            $table->text('chronic_diseases')->nullable(); //امراض مزمنة
            $table->text('current_medications')->nullable(); //الدواء الحالي
            $table->text('allergies')->nullable(); // حساسيات
            $table->text('family_medical_history')->nullable(); //التاريخ العائلي
            $table->boolean('smoker')->default(false);
            $table->string('addiction')->nullable(); //  مدمن
            $table->string('exercise_frequency')->nullable(); //التمارين الرياضبة
            $table->string('diet_description')->nullable(); //وصف النظام الغذائي
            $table->text('current_symptoms')->nullable(); //الاعراض الحالية
            $table->string('recent_vaccinations')->nullable(); //التطعيمات الاخيرة
            $table->timestamps();
        });
    }


};
