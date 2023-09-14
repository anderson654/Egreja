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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(); // Adiciona o campo 'role_id'
            $table->foreign('role_id')->references('id')->on('roles'); // Define a chave estrangeira para a tabela 'roles'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']); // Remove a chave estrangeira
            $table->dropColumn('role_id'); // Remove o campo 'role_id'
        });
    }
};
