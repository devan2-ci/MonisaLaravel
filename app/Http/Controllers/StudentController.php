<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
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

        $students = Student::where('school_id', $school->id)->latest()->get();

        return view('admin.students.index', compact('students', 'school'));
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

        return view('admin.students.create', compact('school'));
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
            'nis' => 'required|string|max:255|unique:students,nis',
            'nisn' => 'required|string|max:255|unique:students,nisn',
            'gender' => 'required|in:l,p'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['nisn'] . '@example.com',
            'password' => Hash::make($validated['nisn']),
        ]);

        $user->assignRole('student');

        Student::create([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'gender' => $validated['gender'],
            'school_id' => $school->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        if ($student->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit siswa ini.');
        }

        return view('admin.students.edit', compact('student', 'school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        if ($student->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit siswa ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:students,nis,' . $student->id,
            'nisn' => 'required|string|max:255|unique:students,nisn,' . $student->id,
            'gender' => 'required|in:l,p'
        ]);

        $user = $student->user;
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['nisn'] . '@example.com',
            'password' => Hash::make($validated['nisn']),
        ]);

        $student->update([
            'name' => $validated['name'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'gender' => $validated['gender'],
        ]);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
