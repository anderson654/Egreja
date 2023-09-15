<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        DB::table('request_statuses')->insert([
            ['title' => 'aberto'],
            ['title' => 'em atendimento'],
            ['title' => 'encerrado'],
            ['title' => 'pendente']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_statuses');
    }
};
