<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ktp', function (Blueprint $table) {
            $table->string('ktp_path')->after('ktp_negara');
            $table->string('ktp_umur', 3)->after('ktp_tgl_lahir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktp', function (Blueprint $table) {
            $table->dropColumn('ktp_path');
            $table->dropColumn('ktp_umur');
        });
    }
};
