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
        Schema::create('ktp', function (Blueprint $table) {
            $table->string('ktp_nik', 16)->primary();
            $table->string('ktp_tempat_lahir', 100);
            $table->date('ktp_tgl_lahir');
            $table->enum('ktp_jk', ['L', 'P']);
            $table->enum('ktp_darah', ['A', 'B', 'AB', 'O']);
            $table->string('ktp_dusun', 150)->nullable();
            $table->string('ktp_rt', 5)->nullable();
            $table->string('ktp_rw', 5)->nullable();
            //
            $table->unsignedBigInteger('kelurahan_id');
            $table->unsignedBigInteger('kecamatan_id');
            $table->unsignedBigInteger('kabupaten_id');
            $table->unsignedBigInteger('provinsi_id');
            //
            $table->enum('ktp_agama', ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->enum('ktp_perkawinan', ['Belum kawin', 'Kawin', 'Cerai hidup', 'Cerai mati', 'Kawin belum tercatat']);
            $table->enum('ktp_negara', ['WNI', 'WNA']);
            $table->date('ktp_berlaku')->nullable();
            $table->date('ktp_dibuat')->nullable();
            $table->timestamps();
            // foreign key
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kabupaten_id')->references('id')->on('kabupaten')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktp');
    }
};
