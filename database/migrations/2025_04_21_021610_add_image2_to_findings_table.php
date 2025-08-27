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
        Schema::table('findings', function (Blueprint $table) {
            $table->string('image2')->nullable()->after('image'); // Menambahkan kolom 'image2' setelah 'image'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->dropColumn('image2'); // Menghapus kolom 'image2' jika migrasi dibatalkan
        });
    }
};
