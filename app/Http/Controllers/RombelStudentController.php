<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RombelStudentController extends Controller
{
    private function getSchool()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        return $admin->school;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Rombel $rombel)
    {
        $school = $this->getSchool();

        // Pastikan rombel milik sekolah admin
        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $students = Student::where('school_id', $school->id)->where('rombel_id', $rombel->id)->orderBy('name')->get();

        return view('admin.rombels.students.index', compact('rombel','students','school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Rombel $rombel)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $students = Student::where('school_id', $school->id)->whereNull('rombel_id')->orderBy('name')->get();

        return view('admin.rombels.students.create', compact('rombel','students','school'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Rombel $rombel)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|integer|exists:students,id',
        ]);

        $students = Student::where('school_id', $school->id)->whereNull('rombel_id')->whereIn('id', $validated['student_ids'])->get();

        foreach ($students as $student) {
            $student->update([
                'rombel_id' => $rombel->id,
            ]);
        }

        return redirect()->route('rombels.students.index', $rombel)->with('success', 'Siswa berhasil ditambahkan ke rombel.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rombel $rombel, Student $student)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        if (
            $student->school_id !== $school->id ||
            $student->rombel_id !== $rombel->id
        ) {
            abort(403, 'Siswa tidak berada di rombel tersebut.');
        }

        $student->update([
            'rombel_id' => null,
        ]);

        return redirect()->route('rombels.students.index', $rombel)->with('success', 'Siswa berhasil dikeluarkan dari rombel.');
    }
}
