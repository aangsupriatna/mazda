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
        Schema::table('tampilans', function (Blueprint $table) {
            $table->string('timezone')->default(config('app.timezone'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tampilans', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
};
