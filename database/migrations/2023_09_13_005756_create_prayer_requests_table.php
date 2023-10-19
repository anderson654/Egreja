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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id(); // Campo 'id' da tabela
            $table->unsignedBigInteger('user_id'); // Campo 'user_id' para registrar quem fez o pedido de ajuda
            $table->string('status'); // Campo 'status' para registrar o status do pedido
            $table->timestamps(); // Campos 'created_at' e 'updated_at'

            // Defina uma chave estrangeira para 'user_id' relacionando com a tabela 'users'
            $table->foreign('user_id')->references('id')->on('users');

            // Adicione outras colunas conforme necessário para registrar informações adicionais do pedido de ajuda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
