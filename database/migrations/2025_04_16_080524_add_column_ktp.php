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
            $table->string('ktp_nama', 200)->after('ktp_nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ktp', function (Blueprint $table) {
            $table->dropColumn('ktp_nama');
        });
    }
};
