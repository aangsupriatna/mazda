<?php

use App\Models\Klien;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proyeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->text('lokasi');
            $table->string('nomor_kontrak');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_serah_terima');
            $table->decimal('nilai_kontrak', 15, 0);
            $table->string('kategori_proyek');
            $table->string('persentase_pekerjaan');
            $table->text('ruang_lingkup_pekerjaan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('konsorsium')->default(false);
            $table->json('lampiran')->nullable();
            $table->json('klasifikasi')->nullable();
            $table->foreignIdFor(Klien::class)->nullable()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
