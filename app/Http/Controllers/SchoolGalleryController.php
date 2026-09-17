<?php

namespace App\Http\Controllers;

use App\Models\SchoolGalleries;
use App\Models\SchoolGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        $galleries = SchoolGallery::where('school_id', $school->id)->latest()->get();

        return view('admin.galleries.index', compact('galleries', 'school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        return view('admin.galleries.create', compact('school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Simpan foto baru
            $validated['photo'] = $request
                ->file('photo')
                ->store('galleries', 'public');
        }

        SchoolGallery::create([
            'school_id' => $school->id,
            'name' => $validated['name'],
            'photo' => $validated['photo'] ?? null,
        ]);

        return redirect()->route('galleries.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolGallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolGallery $gallery)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        // Pastikan gallery memang milik sekolah admin
        if ($gallery->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses ke galeri ini.');
        }

        return view('admin.galleries.edit', compact('gallery','school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolGallery $gallery)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        if ($gallery->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses ke galeri ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($gallery->photo) {
                Storage::disk('public')->delete($gallery->photo);
            }

            // Simpan foto baru
            $validated['photo'] = $request
                ->file('photo')
                ->store('galleries', 'public');
        }

        $gallery->update([
            'name' => $validated['name'],
            'photo' => $validated['photo'] ?? $gallery->photo,
        ]);

        return redirect()->route('galleries.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolGallery $gallery)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        if ($gallery->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses ke galeri ini.');
        }

        // Hapus foto lama jika ada
        if ($gallery->photo) {
            Storage::disk('public')->delete($gallery->photo);
        }

        $gallery->delete();

        return redirect()->route('galleries.index')->with('success', 'Galeri berhasil dihapus.');
    }
}
