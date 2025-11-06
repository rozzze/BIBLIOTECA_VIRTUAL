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
    Schema::create('student_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('dni', 20)->unique();
        $table->foreignId('carrera_id')->nullable()->constrained('careers')->nullOnDelete();
        $table->enum('turno', ['Diurno', 'Nocturno'])->nullable();
        $table->enum('semestre', ['1','2','3','4','5','6'])->nullable();
        $table->string('telefono')->nullable();
        $table->string('direccion')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
