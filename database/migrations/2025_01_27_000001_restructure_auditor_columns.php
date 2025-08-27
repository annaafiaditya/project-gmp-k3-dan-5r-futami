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
            // Hapus kolom auditor lama
            $table->dropColumn('auditor');
            
            // Ganti nama kolom auditee menjadi auditor
            $table->renameColumn('auditee', 'auditor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            // Kembalikan nama kolom auditor menjadi auditee
            $table->renameColumn('auditor', 'auditee');
            
            // Tambah kembali kolom auditor lama
            $table->string('auditor')->nullable()->after('department');
        });
    }
};
