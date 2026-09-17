<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Schools;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::with('user', 'school')->get();
        return view('superadmin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = Schools::orderBy('nama')->get();
        return view('superadmin.admin.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $user->assignRole('admin');

            Admin::create([
                'user_id' => $user->id,
                'name' => $validated['name'] ?? null,
                'school_id' => $validated['school_id'] ?? null,
            ]);
        });

        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $schools = Schools::orderBy('nama')->get();
        return view('superadmin.admin.edit', compact('admin', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $admin->user->id,
            'email' => 'required|string|max:255|unique:users,email,' . $admin->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'school_id' => 'nullable|exists:schools,id',
        ]);

        DB::transaction(function () use ($validated, $admin) {
            $admin->user->update([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => isset($validated['password']) ? bcrypt($validated['password']) : $admin->user->password,
            ]);

            $admin->update([
                'name' => $validated['name'] ?? null,
                'school_id' => $validated['school_id'] ?? null,
            ]);
        });

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->user->delete();
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }
}
