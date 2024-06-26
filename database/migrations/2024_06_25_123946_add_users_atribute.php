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
        Schema::table('musers', function (Blueprint $table) {
            $table->uuid('unid')->after('id');
            $table->string('username')->after('name');
            $table->string('nomer_telp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('musers', function ($table) {
            $table->dropColumn('unid');
            $table->dropColumn('username');
            $table->dropColumn('nomer_telp');
            $table->dropColumn('tempat_lahir');
            $table->dropForeign(['role_id']);
        });
    }
};
