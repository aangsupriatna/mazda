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
        Schema::create('pemiliks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jenis_kepemilikan');
            $table->string('kewarganegaraan');
            $table->string('nik_paspor');
            $table->string('npwp');
            $table->integer('saham');
            $table->string('tipe_saham');
            $table->string('alamat');
            $table->string('kabupaten_kota');
            $table->string('provinsi');
            $table->string('negara');
            $table->foreignId('file_ktp_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('file_npwp_id')->nullable()->constrained('media')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemiliks');
    }
};
