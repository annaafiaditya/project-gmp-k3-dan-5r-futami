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
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->enum('gmp_criteria', ['Pest', 'Infrastruktur', 'Lingkungan', 'Personal Behavior', 'Cleaning']);
            $table->enum('department', ['Produksi', 'Warehouse', 'Engineering', 'HR', 'QA']);
            $table->text('description');
            $table->enum('status', ['Open', 'Close'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
