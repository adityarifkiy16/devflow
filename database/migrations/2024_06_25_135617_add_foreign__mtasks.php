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
        Schema::table('mtasks', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('user_id')->references('id')->on('musers');
            $table->foreign('project_id')->references('id')->on('mprojects');
            $table->foreign('status_id')->references('id')->on('mstatuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mtasks', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('project_id');
            $table->dropForeign('status_id');
        });
    }
};
