<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('prayer_category_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropForeign(['prayer_category_id']);
            $table->dropColumn('prayer_category_id');
        });
    }
};
