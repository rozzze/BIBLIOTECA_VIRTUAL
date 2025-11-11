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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // 📘 Datos principales
            $table->string('title', 200);
            $table->string('slug', 220)->unique();

            // 🔗 Relaciones con otros módulos
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // 📅 Información adicional
            $table->year('publication_year')->nullable();
            $table->string('language', 50)->nullable();
            $table->integer('pages')->nullable();
            $table->integer('stock')->default(1);

            // 🖼️ Imagen de portada
            $table->string('cover_path')->nullable();

            // 🧾 Descripción
            $table->text('summary')->nullable();

            // ⚙️ Estado del libro
            $table->enum('status', ['Disponible', 'Prestado', 'En mantenimiento'])->default('Disponible');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
