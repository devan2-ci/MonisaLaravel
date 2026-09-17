<?php

namespace App\Http\Controllers;

use App\Models\MasterMapel;
use App\Models\SchoolMapel;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
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
        
        $teachers = Teacher::with(['schoolMapel.masterMapel'])->where('school_id', $school->id)->latest()->paginate(10);
        
        return view('admin.teachers.index', compact('teachers', 'school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        $school = $this->getSchool();

        $schoolMapels = SchoolMapel::with('masterMapel')->where('school_id', $school->id)
            ->orderBy(
                MasterMapel::select('name')->whereColumn('master_mapels.id', 'school_mapels.master_mapel_id')
            )->get();

        return view('admin.teachers.create', compact('school','schoolMapels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:teachers,nip',
            'nuptk' => 'required|string|max:20|unique:teachers,nuptk',
            'gender' => 'required|in:l,p',
            'school_mapel_id' => 'required|integer|exists:school_mapels,id',
        ]);

        $schoolMapel = SchoolMapel::where('id', $validated['school_mapel_id'])
            ->where('school_id', $school->id)
            ->first();

        if (!$schoolMapel) {
            return back()
                ->withErrors([
                    'school_mapel_id' => 'Mata pelajaran tidak tersedia di sekolah Anda.'
                ])
                ->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['nuptk'] . '@monisa.com',
            'password' => Hash::make($validated['nuptk']),
        ]);

        $user->assignRole('teacher');

        Teacher::create([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'nuptk' => $validated['nuptk'],
            'gender' => $validated['gender'],
            'school_id' => $school->id,
            'user_id' => $user->id,
            'school_mapel_id' => $validated['school_mapel_id'],
        ]);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $school = $this->getSchool();

        if ($teacher->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit guru ini.');
        }

        $schoolMapels = SchoolMapel::with('masterMapel')
            ->where('school_id', $school->id)
            ->get()
            ->sortBy('masterMapel.name');

        return view('admin.teachers.edit', compact('teacher', 'school','schoolMapels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $school = $this->getSchool();

        if ($teacher->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit guru ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:teachers,nip,' . $teacher->id,
            'nuptk' => 'required|string|max:20|unique:teachers,nuptk,' . $teacher->id,
            'gender' => 'required|in:l,p',
            'school_mapel_id' => 'required|integer|exists:school_mapels,id',
        ]);

        $schoolMapel = SchoolMapel::where('id', $validated['school_mapel_id'])
            ->where('school_id', $school->id)
            ->first();

        if (!$schoolMapel) {
            return back()
                ->withErrors([
                    'school_mapel_id' => 'Mata pelajaran tidak tersedia di sekolah Anda.'
                ])
                ->withInput();
        }

        $user = $teacher->user;

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['nuptk'] . '@monisa.com',
            'password' => Hash::make($validated['nuptk']),
        ]);

        $teacher->update([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'nuptk' => $validated['nuptk'],
            'gender' => $validated['gender'],
            'school_mapel_id' => $validated['school_mapel_id'],
        ]);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
