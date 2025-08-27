<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Finding;
use App\Models\Closing;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class FindingController extends Controller
{

    public function landing()
    {
        return view('landingpage');
    }

    public function home()
    {
        return view('home');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Finding::with('closing');

        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }

        if ($request->has('week') && $request->week != '') {
            $query->where('week', $request->week);
        }

        if ($request->has('jenis_audit') && $request->jenis_audit != '') {
            $query->where('jenis_audit', $request->jenis_audit);
        }

        // Filter status Open/Close (Belum/Sudah Closing)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $year = $request->input('year', now()->year);
        $query->where('year', $year);

        $perPage = $request->input('per_page', 10);
        $findings = $query->paginate($perPage)->appends($request->all());

        // Ambil tahun dari tabel years
        $years = Year::where('is_active', true)
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Hanya hitung jika admin
        $countFindings = auth()->user()?->role === 'admin' ? Finding::count() : null;
        $countStatus = auth()->user()?->role === 'admin' ? Finding::where('status', 'Close')->count() : null;
        $countUsers = auth()->user()?->role === 'admin' ? User::count() : null;

        return view('findings.index', compact(
            'findings',
            'years',
            'year',
            'countFindings',
            'countStatus',
            'countUsers'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('findings.create');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'auditor' => 'required',
            'jenis_audit' => 'required',
            'kriteria' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'week' => 'required|integer|min:1|max:52',
        ]);

        // Simpan file gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('findings', 'public'); // Simpan di storage/app/public/findings
        }

        $finding = Finding::create([
            'department' => $request->department,
            'auditor' => $request->auditor,
            'jenis_audit' => $request->jenis_audit,
            'kriteria' => $request->kriteria,
            'description' => $request->description,
            'image' => $imagePath,
            'week' => $request->week,
            'year' => now()->year,
            'gmp_criteria' => $request->jenis_audit === 'GMP' ? $request->kriteria : null,
        ]);

        // Notifikasi: kirim ke user departemen terkait (non-admin)
        $deptUsers = User::where('department', $request->department)
            ->whereIn('role', ['user', 'guest'])
            ->where('is_verified', true)
            ->get();
        foreach ($deptUsers as $u) {
            Notification::create([
                'user_id' => $u->id,
                'type' => 'finding_created',
                'title' => 'Temuan baru untuk departemen ' . $request->department,
                'message' => 'Auditor: ' . $request->auditor . ' • ' . $request->jenis_audit . ' • ' . $request->kriteria,
                'link_url' => route('findings.index', [
                    'department' => $request->department,
                    'highlight' => $finding->id,
                    'year' => now()->year,
                    'week' => $request->week,
                    'jenis_audit' => $request->jenis_audit,
                ]),
            ]);
        }

        // Redirect ke halaman findings.index dengan filter sesuai input dan highlight data baru
        $redirectParams = [
            'year' => $request->input('year', now()->year),
            'week' => $request->week,
            'jenis_audit' => $request->jenis_audit,
            'department' => $request->department,
            'highlight' => $finding->id,
        ];
        return redirect()->route('findings.index', $redirectParams)->with('success', 'Berhasil menambahkan Finding.');
    }

    public function uploadPhotoForm($id)
    {
        $finding = Finding::findOrFail($id);

        // Cek apakah user berhak mengakses (bukan admin dan departemen sesuai)
        if (auth()->user()->role === 'admin') {
            return redirect()->route('findings.index')
                ->with('error', 'Admin tidak dapat mengupload foto closing');
        }

        if (auth()->user()->department !== $finding->department) {
            return redirect()->route('findings.index')
                ->with('error', 'Hanya Departemen ' . $finding->department . ' yang bisa mengupload foto');
        }

        return view('findings.upload_photo', compact('finding'));
    }

    public function uploadPhotoSubmit(Request $request, $id)
    {
        $finding = Finding::findOrFail($id);

        // Cek apakah user berhak mengupload foto (bukan admin dan departemen sesuai)
        if (auth()->user()->role === 'admin') {
            return redirect()->route('findings.index')
                ->with('error', 'Admin tidak dapat mengupload foto closing');
        }

        if (auth()->user()->department !== $finding->department) {
            return redirect()->route('findings.index')
                ->with('error', 'Hanya Departemen ' . $finding->department . ' yang bisa mengupload foto');
        }

        $request->validate([
            'image2' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_penyelesaian' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('image2')) {
            // Hapus gambar lama kalau ada
            if ($finding->image2) {
                Storage::delete('public/' . $finding->image2);
            }

            $image2Path = $request->file('image2')->store('findings', 'public');
            $finding->image2 = $image2Path;
            $finding->save();
        }

        // Simpan catatan_penyelesaian ke closing
        $closing = $finding->closing ?: new \App\Models\Closing();
        $closing->finding_id = $finding->id;
        $closing->catatan_penyelesaian = $request->catatan_penyelesaian ?: null;
        // Isi kolom wajib jika closing baru
        if (!$closing->exists) {
            $closing->description = '-';
            $closing->gmp_criteria = $finding->gmp_criteria ?? 'Pest';
            $closing->department = $finding->department ?? 'Produksi';
            $closing->status = 'Open';
        }
        $closing->save();

        $message = $request->catatan_penyelesaian ? 'Foto Closing dan catatan berhasil diupload.' : 'Foto Closing berhasil diupload.';

        // Notifikasi: kirim ke admin saat ada upload closing
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'closing_uploaded',
                'title' => 'Closing diunggah (' . $finding->department . ')',
                'message' => 'Finding #' . $finding->id . ' telah diupdate closing.',
                'link_url' => route('findings.index', [
                    'department' => $finding->department,
                    'highlight' => $finding->id,
                    'year' => request('year', now()->year),
                    'week' => request('week'),
                    'jenis_audit' => request('jenis_audit'),
                ]),
            ]);
        }
        
        // Redirect back to filtered page with current filters
        $redirectParams = $request->only(['year', 'week', 'jenis_audit', 'department']);
        $redirectParams['highlight'] = $finding->id;
        return redirect()->route('findings.index', $redirectParams)->with('success', $message);
    }

    public function editPhotoForm($id)
    {
        $finding = Finding::findOrFail($id);

        // Hanya user dari departemen yang sama yang boleh mengakses form edit (bukan admin)
        if (auth()->user()->role === 'admin') {
            return redirect()->route('findings.index')
                ->with('error', 'Admin tidak dapat mengedit foto closing');
        }

        if (auth()->user()->department !== $finding->department) {
            return redirect()->route('findings.index')
                ->with('error', 'Hanya Departemen ' . $finding->department . ' yang bisa mengedit foto');
        }

        return view('findings.edit-photo', compact('finding'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $finding = Finding::findOrFail($id);

        // Cek apakah user dari departemen yang benar (bukan admin)
        if (auth()->user()->role === 'admin') {
            return redirect()->route('findings.index')
                ->with('error', 'Admin tidak dapat mengedit foto closing');
        }

        if (auth()->user()->department !== $finding->department) {
            return redirect()->route('findings.index')
                ->with('error', 'Hanya Departemen ' . $finding->department . ' yang bisa memperbarui foto');
        }

        $request->validate([
            'image2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_penyelesaian' => 'nullable|string|max:1000',
        ]);

        // Update foto hanya jika ada file baru
        if ($request->hasFile('image2')) {
            // Hapus foto lama jika ada
            if ($finding->image2) {
                Storage::delete('public/' . $finding->image2);
            }

            // Simpan foto baru
            $path = $request->file('image2')->store('closing_images', 'public');
            $finding->image2 = $path;
            $finding->save();
        }

        // Simpan catatan_penyelesaian
        $closing = $finding->closing ?: new \App\Models\Closing();
        $closing->finding_id = $finding->id;
        $closing->catatan_penyelesaian = $request->catatan_penyelesaian ?: null;
        // Isi kolom wajib jika closing baru
        if (!$closing->exists) {
            $closing->description = '-';
            $closing->gmp_criteria = $finding->gmp_criteria ?? 'Pest';
            $closing->department = $finding->department ?? 'Produksi';
            $closing->status = 'Open';
        }
        $closing->save();

        $hasNewImage = $request->hasFile('image2');
        $hasCatatan = $request->catatan_penyelesaian;
        
        if ($hasNewImage && $hasCatatan) {
            $message = 'Foto closing dan catatan berhasil diperbarui.';
        } elseif ($hasNewImage) {
            $message = 'Foto closing berhasil diperbarui.';
        } elseif ($hasCatatan) {
            $message = 'Catatan berhasil diperbarui.';
        } else {
            $message = 'Data berhasil diperbarui.';
        }
        
        // Redirect back to filtered page with current filters
        $redirectParams = $request->only(['year', 'week', 'jenis_audit', 'department']);
        $redirectParams['highlight'] = $finding->id;
        return redirect()->route('findings.index', $redirectParams)->with('success', $message);
    }


    public function toggleStatus($id)
    {
        $finding = Finding::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $finding->status = $finding->status === 'Open' ? 'Close' : 'Open';
        $finding->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $finding = Finding::find($id);
        return view('findings.edit', compact('finding'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'department' => 'required',
            'auditor' => 'required',
            'jenis_audit' => 'required',
            'kriteria' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'week' => 'required|integer|min:1|max:52',
        ]);

        $finding = Finding::find($id);

        // Update gambar jika ada file baru
        if ($request->hasFile('image')) {
            if ($finding->image) {
                Storage::delete('public/' . $finding->image); // Hapus gambar lama
            }
            $imagePath = $request->file('image')->store('findings', 'public');
            $finding->image = $imagePath;
        }

        $finding->update([
            'department' => $request->department,
            'auditor' => $request->auditor,
            'jenis_audit' => $request->jenis_audit,
            'kriteria' => $request->kriteria,
            'description' => $request->description,
            'image' => $finding->image,
            'week' => $request->week,
            'gmp_criteria' => $request->jenis_audit === 'GMP' ? $request->kriteria : null,
        ]);

        // Redirect ke halaman findings.index dengan filter sesuai input dan highlight data yang diedit
        $redirectParams = [
            'year' => $request->input('year', now()->year),
            'week' => $request->week,
            'jenis_audit' => $request->jenis_audit,
            'department' => $request->department,
            'highlight' => $finding->id,
        ];
        return redirect()->route('findings.index', $redirectParams)->with('success', 'Berhasil mengedit Finding.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $findings = Finding::find($id);
        $findings->delete();

        return redirect()->route('findings.index')->with('success', 'Data berhasil dihapus.');
    }

    public function deletePhotoAfter($id)
    {
        $finding = Finding::findOrFail($id);
        
        // Hanya user dari departemen yang sama yang bisa menghapus foto (bukan admin)
        if (auth()->user()->role === 'admin') {
            return redirect()->route('findings.index')
                ->with('error', 'Admin tidak dapat menghapus foto closing');
        }

        if (auth()->user()->department !== $finding->department) {
            return redirect()->route('findings.index')
                ->with('error', 'Hanya Departemen ' . $finding->department . ' yang bisa menghapus foto');
        }

        // Hapus foto after
        if ($finding->image2) {
            \Storage::delete('public/' . $finding->image2);
            $finding->image2 = null;
            $finding->save();
        }
        // Hapus data closing jika ada
        if ($finding->closing) {
            $finding->closing->delete();
        }
        
        // Redirect back to filtered page with current filters
        $redirectParams = request()->only(['year', 'week', 'jenis_audit', 'department']);
        $redirectParams['highlight'] = $finding->id;
        return redirect()->route('findings.index', $redirectParams)->with('success', 'Foto after & catatan berhasil dihapus.');
    }
}
