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
        Schema::create('prayer_categories', function (Blueprint $table) {
            $table->id(); // Campo de ID auto-incremento
            $table->string('title'); // Campo para o título da categoria
            $table->timestamps(); // Campos created_at e updated_at para controle de data e hora de criação/atualização
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_categories');
    }
};
