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
        // Tambahkan data tahun default jika belum ada
        $years = [
            [
                'year' => 2025,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2026,
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($years as $year) {
            if (!DB::table('years')->where('year', $year['year'])->exists()) {
                DB::table('years')->insert($year);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus data tahun default
        DB::table('years')->whereIn('year', [2025, 2026])->delete();
    }
};
