<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guardians = Guardian::with('students')->latest()->paginate(10);

        return view('admin.guardians.index', compact('guardians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();

        return view('admin.guardians.create', compact('students'));
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
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'relationship' => 'required|in:ayah,ibu,wali',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['name'] . rand(10, 99),
            'email' => $validated['email'],
            'password' =>Hash::make($validated['phone']), 
        ]);

        $user->assignRole('parent');

        $student = Student::findOrFail($validated['student_id']);

        // Maksimal 3 wali
        if ($student->guardians()->count() >= 3) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Siswa sudah memiliki maksimal 3 wali.',
                ]);
        }

        Guardian::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'relationship' => $validated['relationship'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('guardians.index')->with('success', 'Wali berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guardian $guardian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guardian $guardian)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $students = Student::orderBy('name')->get();

        $guardian->load('students');

        return view('admin.guardians.edit', compact('guardian','students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guardian $guardian)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'in:ayah,ibu,wali'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $user = $guardian->user;
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $student = Student::findOrFail($validated['student_id']);

        // Jika pindah ke siswa lain, cek batas 3 wali
        if (
            $guardian->student_id != $student->id &&
            $student->guardians()->count() >= 3
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Siswa tersebut sudah memiliki maksimal 3 wali.',
                ]);
        }

        $guardian->update([
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'relationship' => $validated['relationship'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('guardians.index')->with('success', 'Wali berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guardian $guardian)
    {
        $guardian->user->delete();
        $guardian->delete();

        return redirect()->route('guardians.index')->with('success', 'Wali berhasil dihapus.');
    }
}
