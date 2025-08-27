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
            // Tambah kolom department jika belum ada
            if (!Schema::hasColumn('findings', 'department')) {
                $table->string('department')->nullable()->after('image2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            if (Schema::hasColumn('findings', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};
