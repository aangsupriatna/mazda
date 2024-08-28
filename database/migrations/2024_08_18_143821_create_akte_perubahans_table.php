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
        Schema::create('akte_perubahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->string('nomor');
            $table->date('tanggal');
            $table->string('nama_notaris');
            $table->string('nomor_pengesahan');
            $table->date('tanggal_pengesahan');
            $table->foreignId('file_akte_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('file_pengesahan_id')->nullable()->constrained('media')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akte_perubahans');
    }
};
