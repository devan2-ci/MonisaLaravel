<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\SchoolMajor;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RombelController extends Controller
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
    public function index()
    {
        $school = $this->getSchool();

        $rombels = Rombel::with(['teacher','schoolMajor.major'])->where('school_id', $school->id)->latest()->get();

        return view('admin.rombels.index', compact('rombels','school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = $this->getSchool();

        $teachers = Teacher::where('school_id', $school->id)->orderBy('name')->get();

        $schoolMajors = SchoolMajor::with('major')->where('school_id', $school->id)->get();

        return view('admin.rombels.create', compact('school','teachers','schoolMajors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|size:9|regex:/^\d{4}\/\d{4}$/',
            'teacher_id' => 'required|exists:teachers,id',
            'school_major_id' => 'required|exists:school_majors,id',
            'jenjang' => 'required|string|max:10',
            'name' => 'required|string|max:10',
        ]);

        $teacher = Teacher::where('id', $validated['teacher_id'])->where('school_id', $school->id)->first();

        $teacherAlreadyWalas = Rombel::where('school_id', $school->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->exists();

        if ($teacherAlreadyWalas) {
            return back()
                ->withErrors([
                    'teacher_id' => 'Guru tersebut sudah menjadi wali kelas pada tahun ajaran ' .
                        $validated['tahun_ajaran'] . '.'
                ])
                ->withInput();
        }

        $schoolMajor = SchoolMajor::where('id', $validated['school_major_id'])->where('school_id', $school->id)->first();

        if (!$schoolMajor) {
            abort(403, 'Jurusan tidak tersedia di sekolah Anda.');
        }

        $exists = Rombel::where('school_id', $school->id)
            ->where('jenjang', $validated['jenjang'])
            ->where('school_major_id', $validated['school_major_id'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'urutan' => 'Kelas dengan kombinasi tersebut sudah tersedia.'
                ]);
        }

        $qrCode = 'ROMBEL-' . strtoupper(Str::random(20));

        Rombel::create([
            'school_id' => $school->id,
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'teacher_id' => $validated['teacher_id'],
            'school_major_id' => $validated['school_major_id'],
            'jenjang' => $validated['jenjang'],
            'name' => $validated['name'],
            'qr_code' => $qrCode,
            'is_active' => true,
        ]);

        return redirect()->route('rombels.index')->with('success', 'Rombel berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rombel $rombel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rombel $rombel)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $teachers = Teacher::where('school_id', $school->id)->orderBy('name')->get();

        $schoolMajors = SchoolMajor::with('major')->where('school_id', $school->id)->get();

        return view('admin.rombels.edit', compact('school','teachers','schoolMajors','rombel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rombel $rombel)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|size:9|regex:/^\d{4}\/\d{4}$/',
            'teacher_id' => 'required|exists:teachers,id',
            'school_major_id' => 'required|exists:school_majors,id',
            'jenjang' => 'required|string|max:10',
            'name' => 'required|string|max:10',
            'is_active' => 'required|boolean',
        ]);

        $teacher = Teacher::where('id', $validated['teacher_id'])->where('school_id', $school->id)->exists();

        if (!$teacher) {
            abort(403, 'Guru tidak berasal dari sekolah Anda.');
        }

        $teacherAlreadyWalas = Rombel::where('school_id', $school->id)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('id', '!=', $rombel->id)
            ->exists();

        if ($teacherAlreadyWalas) {
            return back()
                ->withErrors([
                    'teacher_id' => 'Guru tersebut sudah menjadi wali kelas pada rombel lain di tahun ajaran ' .
                        $validated['tahun_ajaran'] . '.'
                ])
                ->withInput();
        }

        $schoolMajor = SchoolMajor::where('id', $validated['school_major_id'])->where('school_id', $school->id)->exists();

        if (!$schoolMajor) {
            abort(403, 'Jurusan tidak tersedia di sekolah Anda.');
        }

        $exists = Rombel::where('school_id', $school->id)
            ->where('jenjang', $validated['jenjang'])
            ->where('school_major_id', $validated['school_major_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $rombel->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors([
                    'name' => 'Kelas dengan kombinasi tersebut sudah tersedia.'
                ]);
        }

        $rombel->update([
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'teacher_id' => $validated['teacher_id'],
            'school_major_id' => $validated['school_major_id'] ?? null,
            'jenjang' => $validated['jenjang'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('rombels.index')->with('success', 'Rombel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rombel $rombel)
    {
        $school = $this->getSchool();

        if ($rombel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $rombel->delete();

        return redirect()->route('rombels.index')->with('success', 'Rombel berhasil dihapus.');
    }
}
