<?php

use App\Enums\Setting\Font;
use App\Enums\Setting\PrimaryColor;
use App\Enums\Setting\RecordsPerPage;
use Illuminate\Support\Facades\Schema;
use App\Enums\Setting\TableSortDirection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tampilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('primary_color')->default(PrimaryColor::DEFAULT);
            $table->string('font')->default(Font::DEFAULT);
            $table->string('table_sort_direction')->default(TableSortDirection::DEFAULT);
            $table->string('records_per_page')->default(RecordsPerPage::DEFAULT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tampilans');
    }
};
