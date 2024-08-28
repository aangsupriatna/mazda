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
        Schema::create('peralatan_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peralatan_id')->constrained('peralatans')->onDelete('cascade');
            $table->string('peminjam');
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_peminjaman')->nullable();
            $table->date('tanggal_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peralatan_statuses');
    }
};
