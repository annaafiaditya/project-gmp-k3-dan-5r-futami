<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Finding;

class Finding2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data untuk tahun 2026
        $findings2026 = [
            [
                'image' => null,
                'gmp_criteria' => 'Pest',
                'department' => 'Produksi',
                'description' => 'Terdapat tikus di area produksi yang dapat mengkontaminasi produk',
                'status' => 'Open',
                'week' => 1,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Produksi',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Pest Control',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Infrastruktur',
                'department' => 'Engineering',
                'description' => 'Lantai produksi terdapat retakan yang dapat menjadi sarang kuman',
                'status' => 'Open',
                'week' => 2,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Engineering',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Infrastructure',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Lingkungan',
                'department' => 'Warehouse',
                'description' => 'Area penyimpanan terlalu lembab, dapat merusak produk',
                'status' => 'Open',
                'week' => 3,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Warehouse',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Environment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Personal Behavior',
                'department' => 'Produksi',
                'description' => 'Operator tidak menggunakan APD sesuai standar',
                'status' => 'Open',
                'week' => 4,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Produksi',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Personal Hygiene',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Cleaning',
                'department' => 'Produksi',
                'description' => 'Mesin produksi tidak dibersihkan secara rutin',
                'status' => 'Open',
                'week' => 5,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Produksi',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Cleaning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Pest',
                'department' => 'Warehouse',
                'description' => 'Terdapat serangga di area penyimpanan bahan baku',
                'status' => 'Open',
                'week' => 6,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Warehouse',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Pest Control',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Infrastruktur',
                'department' => 'Engineering',
                'description' => 'Pencahayaan di area produksi kurang memadai',
                'status' => 'Open',
                'week' => 7,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Engineering',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Infrastructure',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Lingkungan',
                'department' => 'Produksi',
                'description' => 'Suhu ruang produksi tidak sesuai standar',
                'status' => 'Open',
                'week' => 8,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Produksi',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Environment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Personal Behavior',
                'department' => 'HR',
                'description' => 'Karyawan tidak mencuci tangan sebelum masuk area produksi',
                'status' => 'Open',
                'week' => 9,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'HR',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Personal Hygiene',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => null,
                'gmp_criteria' => 'Cleaning',
                'department' => 'Warehouse',
                'description' => 'Area penyimpanan tidak dibersihkan secara berkala',
                'status' => 'Open',
                'week' => 10,
                'year' => 2026,
                'auditor' => 'QA',
                'auditee' => 'Warehouse',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Cleaning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data untuk tahun 2026
        foreach ($findings2026 as $finding) {
            Finding::create($finding);
        }

        $this->command->info('Data finding tahun 2026 berhasil ditambahkan!');
    }
}
