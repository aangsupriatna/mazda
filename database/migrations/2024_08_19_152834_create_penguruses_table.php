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
        Schema::create('penguruses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jenis_kepengurusan');
            $table->boolean('orang_asli_papua')->default(false);
            $table->string('kewarganegaraan');
            $table->string('no_ktp');
            $table->string('npwp');
            $table->integer('no_bpjs_kesehatan')->nullable();
            $table->integer('no_bpjs_ketenagakerjaan')->nullable();
            $table->text('alamat');
            $table->string('provinsi')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('jabatan');
            $table->date('menjabat_sampai')->nullable();
            $table->string('status')->nullable();
            $table->boolean('masih_bekerja')->default(true);
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
        Schema::dropIfExists('penguruses');
    }
};
