<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil tahun dari tabel years
        $years = Year::orderBy('year', 'desc')->get();
        
        // Hitung jumlah data untuk setiap tahun
        foreach ($years as $year) {
            $year->data_count = Finding::where('year', $year->year)->count();
        }

        return view('admin.years.index', compact('years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2025|max:2100',
        ]);

        $year = $request->year;

        // Cek apakah tahun sudah ada di tabel years
        $existingYear = Year::where('year', $year)->first();
        if ($existingYear) {
            return redirect()->route('admin.years.index')
                ->with('error', 'Tahun ' . $year . ' sudah ada dalam database.');
        }

        // Simpan tahun ke database
        Year::create([
            'year' => $year,
            'is_active' => true
        ]);

        return redirect()->route('admin.years.index')
            ->with('success', 'Tahun ' . $year . ' berhasil ditambahkan. Data finding dapat ditambahkan sesuai kebutuhan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($year)
    {
        // Hapus semua data untuk tahun tertentu
        $deletedCount = Finding::where('year', $year)->delete();

        // Hapus tahun dari tabel years
        Year::where('year', $year)->delete();

        return redirect()->route('admin.years.index')
            ->with('success', 'Tahun ' . $year . ' dan ' . $deletedCount . ' data berhasil dihapus.');
    }
}
