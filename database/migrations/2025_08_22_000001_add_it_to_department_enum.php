<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('findings')) {
            DB::statement("ALTER TABLE findings MODIFY COLUMN department ENUM('AG', 'Engineering', 'HR', 'IT', 'Produksi', 'QA', 'Warehouse')");
        }

        if (Schema::hasTable('closings')) {
            DB::statement("ALTER TABLE closings MODIFY COLUMN department ENUM('AG', 'Engineering', 'HR', 'IT', 'Produksi', 'QA', 'Warehouse')");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('findings')) {
            DB::statement("ALTER TABLE findings MODIFY COLUMN department ENUM('AG', 'Engineering', 'HR', 'Produksi', 'QA', 'Warehouse')");
        }

        if (Schema::hasTable('closings')) {
            DB::statement("ALTER TABLE closings MODIFY COLUMN department ENUM('AG', 'Engineering', 'HR', 'Produksi', 'QA', 'Warehouse')");
        }
    }
};


