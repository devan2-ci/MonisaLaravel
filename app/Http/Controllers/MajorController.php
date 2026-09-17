<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::latest()->paginate(5);

        return view('superadmin.major.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.major.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_jur' => 'nullable|string|max:10',
        ]);

        Major::create($validated);

        return redirect()->route('majors.index')->with('success', 'Jurusan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return view('superadmin.major.edit', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_jur' => 'nullable|string|max:10',
        ]);

        $major->update($validated);

        return redirect()->route('majors.index')->with('success', 'Jurusan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('majors.index')->with('success', 'Jurusan deleted successfully.');
    }
}
