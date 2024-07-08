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
        Schema::table('timages', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('mtasks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timages', function (Blueprint $table) {
            $table->dropForeign('task_id');
        });
    }
};
