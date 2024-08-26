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
        Schema::create('tenaga_ahlis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jenis_tenaga_ahli');
            $table->string('kewarganegaraan');
            $table->string('nik_paspor');
            $table->string('npwp');
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->string('negara_tempat_lahir');
            $table->string('kabupaten_kota_tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->string('nomor_telepon_hp');
            $table->string('email');
            $table->string('website')->nullable();
            $table->text('alamat');
            $table->string('provinsi');
            $table->string('kabupaten_kota');
            $table->string('status_kepegawaian');
            $table->integer('lama_pengalaman_kerja');
            $table->string('profesi_keahlian');
            $table->json('pendidikan');
            $table->json('sertifikasi_keahlian');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_ahlis');
    }
};
