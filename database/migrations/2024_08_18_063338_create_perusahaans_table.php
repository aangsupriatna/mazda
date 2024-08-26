<?php

use App\Models\Perusahaan;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('email')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->foreignId('logo_id')->nullable()->references('id')->on('media')->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('perusahaan_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Perusahaan::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
        Schema::dropIfExists('perusahaan_user');
    }
};
