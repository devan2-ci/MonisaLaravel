<?php

namespace App\Http\Controllers;

use App\Models\MasterMapel;
use Illuminate\Http\Request;

class MasterMapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = MasterMapel::latest()->paginate(5);

        return view('superadmin.mapel.index', compact('mapels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.mapel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_mapel' => 'nullable|string|max:30',
        ]);

        MasterMapel::create($validated);

        return redirect()->route('mapels.index')->with('success', 'Mata Pelajaran created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterMapel $masterMapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterMapel $mapel)
    {
        return view('superadmin.mapel.edit', compact('mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterMapel $mapel)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'kode_mapel' => 'nullable|string|max:30',
        ]);

        $mapel->update($validated);

        return redirect()->route('mapels.index')->with('success', 'Mata Pelajaran updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterMapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('mapels.index')->with('success', 'Mata Pelajaran deleted successfully.');
    }
}
