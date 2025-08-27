<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->enum('gmp_criteria', ['Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning'])
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->enum('gmp_criteria', ['Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning'])
                ->nullable(false)
                ->change();
        });
    }
}; 