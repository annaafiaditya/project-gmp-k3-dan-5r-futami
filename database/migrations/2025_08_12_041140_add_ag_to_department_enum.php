<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update findings table
        DB::statement("ALTER TABLE findings MODIFY COLUMN department ENUM('Produksi', 'Warehouse', 'Engineering', 'HR', 'QA', 'AG')");
        
        // Update closings table if exists
        if (Schema::hasTable('closings')) {
            DB::statement("ALTER TABLE closings MODIFY COLUMN department ENUM('Produksi', 'Warehouse', 'Engineering', 'HR', 'QA', 'AG')");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert findings table
        DB::statement("ALTER TABLE findings MODIFY COLUMN department ENUM('Produksi', 'Warehouse', 'Engineering', 'HR', 'QA')");
        
        // Revert closings table if exists
        if (Schema::hasTable('closings')) {
            DB::statement("ALTER TABLE closings MODIFY COLUMN department ENUM('Produksi', 'Warehouse', 'Engineering', 'HR', 'QA')");
        }
    }
};
