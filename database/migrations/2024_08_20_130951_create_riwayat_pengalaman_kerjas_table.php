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
        Schema::create('riwayat_pengalaman_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->foreignId('tenaga_ahli_id')->constrained('tenaga_ahlis')->onDelete('cascade');
            $table->string('tahun');
            $table->text('nama_proyek');
            $table->string('lokasi_proyek');
            $table->string('pengguna_jasa');
            $table->string('nama_perusahaan');
            $table->string('uraian_tugas');
            $table->string('posisi_penugasan');
            $table->string('status_kepegawaian');
            $table->string('surat_referensi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengalaman_kerjas');
    }
};
