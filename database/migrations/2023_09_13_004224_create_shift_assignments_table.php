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
        Schema::create('shift_assignments', function (Blueprint $table) {
            $table->id(); // Campo 'id' da tabela
            $table->unsignedBigInteger('user_id'); // Campo 'user_id' para relacionar com a tabela 'users'
            $table->dateTime('start_time'); // Hora de início do turno
            $table->dateTime('end_time'); // Hora de término do turno
            $table->timestamps(); // Campos 'created_at' e 'updated_at'

            // Defina uma chave estrangeira para 'user_id' relacionando com a tabela 'users'
            $table->foreign('user_id')->references('id')->on('users');

            // Adicione outras colunas conforme necessário para registrar informações do turno
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_assignments');
    }
};
