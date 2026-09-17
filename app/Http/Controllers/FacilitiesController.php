<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilitiesController extends Controller
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

        $facilities = Facilities::where('school_id', $school->id)->latest()->get();
        return view('admin.facilities.index', compact('facilities', 'school'));
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

        return view('admin.facilities.create', compact('school'));
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
                ->store('facilities', 'public');
        }

        Facilities::create([
            'school_id' => $school->id,
            'name' => $validated['name'],
            'image' => $validated['photo'] ?? null,
        ]);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facilities $facilities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facilities $facility)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $facility = Facilities::findOrFail($facility->id);

        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facilities $facility)
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
            // Hapus foto lama jika ada
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }

            // Simpan foto baru
            $validated['photo'] = $request
                ->file('photo')
                ->store('facilities', 'public');
        }

        $facility->update([
            'name' => $validated['name'],
            'image' => $validated['photo'] ?? $facility->photo,
        ]);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facilities $facility)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        // Hapus foto lama jika ada
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }

        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
