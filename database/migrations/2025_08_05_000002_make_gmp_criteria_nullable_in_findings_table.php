<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan raw SQL untuk mengubah enum menjadi nullable
        DB::statement("ALTER TABLE findings MODIFY COLUMN gmp_criteria ENUM('Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning') NULL");
    }

    public function down(): void
    {
        // Kembalikan ke not null
        DB::statement("ALTER TABLE findings MODIFY COLUMN gmp_criteria ENUM('Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning') NOT NULL");
    }
}; 