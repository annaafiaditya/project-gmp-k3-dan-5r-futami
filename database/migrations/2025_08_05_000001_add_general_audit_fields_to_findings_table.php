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
            $table->string('auditor')->nullable()->after('department');
            $table->string('auditee')->nullable()->after('auditor');
            $table->enum('jenis_audit', ['GMP', 'K3', '5R'])->nullable()->after('auditee');
            $table->string('kriteria')->nullable()->after('jenis_audit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->dropColumn(['auditor', 'auditee', 'jenis_audit', 'kriteria']);
        });
    }
}; 