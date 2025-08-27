<?php

namespace App\Http\Controllers;

use App\Models\Closing;
use App\Models\Notification;
use App\Models\Finding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClosingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $closings = Closing::with('finding')->get();
        return view('closings.index', compact('closings'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('closings.create');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'gmp_criteria' => 'required',
            'department' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg', // Validasi file
        ]);

        // Simpan file gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('closings', 'public'); // Simpan di storage/app/public/closings
        }

        Closing::create([
            'gmp_criteria' => $request->gmp_criteria,
            'department' => $request->department,
            'description' => $request->description,
            'image' => $imagePath, // Simpan path gambar
        ]);

        // Notifikasi admin: ada closing baru
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'closing_uploaded',
                'title' => 'Closing baru diunggah (' . $request->department . ')',
                'message' => $request->gmp_criteria . ' • ' . mb_strimwidth($request->description, 0, 60, '...'),
                'link_url' => route('findings.index', ['department' => $request->department]),
            ]);
        }

        return redirect()->route('closings.index')->with('success', 'Berhasil menambahkan Closing');
    }

    public function toggleStatus($id)
    {
        $closing = Closing::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $closing->status = $closing->status === 'Open' ? 'Close' : 'Open';
        $closing->save();

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $closing = Closing::find($id);
        return view('closings.edit', compact('closing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'gmp_criteria' => 'required',
            'department' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $closing = Closing::find($id);

        // Update gambar jika ada file baru
        if ($request->hasFile('image')) {
            if ($closing->image) {
                Storage::delete('public/' . $closing->image); // Hapus gambar lama
            }
            $imagePath = $request->file('image')->store('closings', 'public');
            $closing->image = $imagePath;
        }

        $closing->update([
            'gmp_criteria' => $request->gmp_criteria,
            'department' => $request->department,
            'description' => $request->description,
            'image' => $closing->image,
        ]);

        return redirect()->route('closings.index')->with('success', 'Berhasil mengedit Closing');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $closings = Closing::find($id);
        $closings->delete();

        return redirect()->route('closings.index')->with('success', 'Data berhasil dihapus.');
    }
}
