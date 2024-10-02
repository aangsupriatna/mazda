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
        Schema::create('izin_usahas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('jenis_izin');
            $table->string('nomor_izin');
            $table->string('pemberi_izin');
            $table->string('kualifikasi_izin')->nullable();
            $table->date('tanggal_izin');
            $table->date('tanggal_kadaluarsa');
            $table->foreignId('file_izin_id')->nullable()->constrained('media')->nullOnDelete();
            $table->text('kualifikasi_usaha')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_usahas');
    }
};
