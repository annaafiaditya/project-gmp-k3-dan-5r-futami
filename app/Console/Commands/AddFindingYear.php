<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Finding;

class AddFindingYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finding:add-year {year : Tahun yang akan ditambahkan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan data finding untuk tahun tertentu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year');
        
        // Cek apakah data untuk tahun ini sudah ada
        $existingCount = Finding::where('year', $year)->count();
        if ($existingCount > 0) {
            $this->warn("Data untuk tahun {$year} sudah ada ({$existingCount} data).");
            if (!$this->confirm('Apakah Anda ingin menambahkan data lagi?')) {
                $this->info('Operasi dibatalkan.');
                return;
            }
        }

        $this->info("Menambahkan data finding untuk tahun {$year}...");

        $findings = [
            [
                'image' => null,
                'gmp_criteria' => 'Pest',
                'department' => 'Produksi',
                'description' => 'Terdapat tikus di area produksi yang dapat mengkontaminasi produk',
                'status' => 'Open',
                'week' => 1,
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
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
                'year' => $year,
                'auditor' => 'QA',
                'auditee' => 'Warehouse',
                'jenis_audit' => 'GMP',
                'kriteria' => 'Cleaning',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $bar = $this->output->createProgressBar(count($findings));
        $bar->start();

        foreach ($findings as $finding) {
            Finding::create($finding);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Data finding tahun {$year} berhasil ditambahkan!");
    }
}
