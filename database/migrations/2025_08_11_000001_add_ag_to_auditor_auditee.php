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
        // Ubah kolom department di tabel users menjadi VARCHAR yang lebih besar
        Schema::table('users', function (Blueprint $table) {
            $table->string('department', 100)->nullable()->change();
        });
        
        // Ubah kolom department di tabel findings menjadi VARCHAR yang lebih besar
        Schema::table('findings', function (Blueprint $table) {
            $table->string('department', 100)->nullable()->change();
        });
        
        // Ubah kolom auditor dan auditee di tabel findings menjadi VARCHAR yang lebih besar
        Schema::table('findings', function (Blueprint $table) {
            $table->string('auditor', 100)->nullable()->change();
            $table->string('auditee', 100)->nullable()->change();
        });
        
        // Ubah kolom department di tabel closings jika ada
        if (Schema::hasTable('closings')) {
            Schema::table('closings', function (Blueprint $table) {
                $table->string('department', 100)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback ke ukuran semula
        Schema::table('users', function (Blueprint $table) {
            $table->string('department', 20)->nullable()->change();
        });
        
        Schema::table('findings', function (Blueprint $table) {
            $table->string('department', 20)->nullable()->change();
            $table->string('auditor', 20)->nullable()->change();
            $table->string('auditee', 20)->nullable()->change();
        });
        
        if (Schema::hasTable('closings')) {
            Schema::table('closings', function (Blueprint $table) {
                $table->string('department', 20)->nullable()->change();
            });
        }
    }
};
