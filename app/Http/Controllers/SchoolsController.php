<?php

namespace App\Http\Controllers;

use App\Models\Schools;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = Schools::all();
        return view('superadmin.school.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.school.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_sekolah' => 'required|string|max:8|unique:schools,kode_sekolah',
            'nama' => 'required|string|max:255',
        ]);

        Schools::create($validated);

        return redirect()->route('schools.index')->with('success', 'School created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schools $schools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schools $schools)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schools $schools)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schools $school)
    {
        $school->delete();

        return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
    }
}
