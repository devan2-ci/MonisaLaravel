<?php

namespace App\Http\Controllers;

use App\Models\MasterMapel;
use App\Models\SchoolMapel;
use App\Models\Teacher;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchoolMapelController extends Controller
{

    /**
     * Mendapatkan school milik admin yang sedang login.
     */
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

        $schoolMapels = SchoolMapel::with('masterMapel')->where('school_id', $school->id)->latest()->get();

        return view('admin.school_mapels.index',compact('schoolMapels', 'school'));
    }

    /**
     * Menampilkan data guru
     */
    public function teachers(SchoolMapel $schoolMapel)
    {
        $school = $this->getSchool();

        if ($schoolMapel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $teachers = $schoolMapel->teachers()->orderBy('name')->get();

        return view('admin.school_mapels.teachers.index',compact('school','schoolMapel','teachers'));
    }

    /**
     * Tambah guru ke mapel
     */
    public function createTeacher(SchoolMapel $schoolMapel)
    {
        $school = $this->getSchool();

        if ($schoolMapel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Ambil semua guru dari sekolah admin yang BELUM terhubung dengan mapel ini.
        $teachers = Teacher::where('school_id', $school->id)
            ->whereDoesntHave('schoolMapels', function ($query) use ($schoolMapel) {
                $query->where(
                    'school_mapels.id',
                    $schoolMapel->id
                );
            })
            ->orderBy('name')
            ->get();

        return view('admin.school_mapels.teachers.create',compact('school','schoolMapel','teachers'));
    }

    /**
     * Simpan guru ke mapel
     */
    public function storeTeacher(Request $request, SchoolMapel $schoolMapel)
    {
        $school = $this->getSchool();

        if ($schoolMapel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
        ]);

        $teacher = Teacher::where('id', $validated['teacher_id'])->where('school_id', $school->id)->first();

        if (!$teacher) {
            abort(
                403,
                'Guru tersebut bukan berasal dari sekolah Anda.'
            );
        }

        // Cek apakah guru sudah mengajar mapel tersebut.
        $alreadyExists = $schoolMapel
            ->teachers()
            ->where('teachers.id', $teacher->id)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withErrors([
                    'teacher_id' =>
                        'Guru tersebut sudah mengajar mata pelajaran ini.'
                ])
                ->withInput();
        }

        // Hubungkan guru dengan mapel.
        $schoolMapel->teachers()->attach($teacher->id);

        return redirect()->route('school_mapel.teachers',$schoolMapel->id)->with('success','Guru berhasil ditambahkan ke mata pelajaran.');
    }

    /**
     * Lepas guru dari mapel
     */
    public function destroyTeacher(SchoolMapel $schoolMapel, Teacher $teacher)
    {
        $school = $this->getSchool();

        if (
            $schoolMapel->school_id !== $school->id ||
            $teacher->school_id !== $school->id
        ) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $schoolMapel->teachers()->detach($teacher->id);

        return redirect()->route('school_mapel.teachers',$schoolMapel->id)->with('success','Guru berhasil dihapus dari mata pelajaran.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = $this->getSchool();

        $mapels = MasterMapel::orderBy('name')->get();

        // Mapel yang sudah dimiliki sekolah
        $selectedMapelIds = SchoolMapel::where('school_id', $school->id)
            ->pluck('master_mapel_id')
            ->toArray();

        return view('admin.school_mapels.create',compact(
            'school', 
            'mapels',
            'selectedMapelIds')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'master_mapel_ids' => 'nullable|array',
            'master_mapel_ids.*' => 'required|exists:master_mapels,id',
        ]);

        $selectedIds = collect($validated['master_mapel_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        DB::transaction(function () use ($school, $selectedIds) {

            /*
             * Ambil semua data, termasuk yang sudah soft delete.
             */
            $existing = SchoolMapel::withTrashed()
                ->where('school_id', $school->id)
                ->get()
                ->groupBy('master_mapel_id');


            /*
             * Sinkronisasi mapel.
             */
            foreach ($selectedIds as $mapelId) {

                /*
                 * Kalau sudah ada record dengan major_id tersebut,
                 * gunakan satu record saja.
                 */
                if (isset($existing[$mapelId])) {

                    $records = $existing[$mapelId];

                    // Ambil record pertama sebagai record utama
                    $mainRecord = $records->first();

                    // Restore kalau sebelumnya soft delete
                    if ($mainRecord->trashed()) {
                        $mainRecord->restore();
                    }

                    /*
                     * Hapus permanen record duplikat.
                     */
                    foreach ($records->skip(1) as $duplicate) {
                        $duplicate->forceDelete();
                    }

                } else {

                    /*
                     * Kalau belum pernah ada, buat baru.
                     */
                    SchoolMapel::create([
                        'school_id' => $school->id,
                        'master_mapel_id' => $mapelId,
                    ]);
                }
            }


            /*
             * Jurusan yang tidak dipilih akan di-soft delete.
             */
            $current = SchoolMapel::where('school_id', $school->id)
                ->get();

            foreach ($current as $schoolMapel) {

                if (!$selectedIds->contains($schoolMapel->master_mapel_id)) {
                    $schoolMapel->delete();
                }
            }
        });

        return redirect()->route('school_mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolMapel $schoolMapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $school = $this->getSchool();

        /*
         * Ambil SEMUA mapel dari master SuperAdmin.
         */
        $mapels = MasterMapel::orderBy('name')->get();

        /*
         * Ambil ID mapel yang sedang aktif di sekolah.
         */
        $selectedMapelIds = SchoolMapel::where('school_id', $school->id)
            ->pluck('master_mapel_id')
            ->toArray();
        
        return view('admin.school_mapels.edit', compact(
            'school',
            'mapels',
            'selectedMapelIds'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'master_mapel_ids' => 'nullable|array',
            'master_mapel_ids.*' => 'required|exists:master_mapels,id',
        ]);

        $selectedIds = collect($validated['master_mapel_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        
        DB::transaction(function () use ($school, $selectedIds) {

            /*
             * Ambil SEMUA record school_mapel,
             * termasuk yang sudah soft delete.
             */
            $existing = SchoolMapel::withTrashed()
                ->where('school_id', $school->id)
                ->get()
                ->groupBy('master_mapel_id');


            /*
             * 1. Aktifkan mapel yang dicentang.
             */
            foreach ($selectedIds as $mapelId) {

                if (isset($existing[$mapelId])) {

                    $records = $existing[$mapelId];

                    /*
                     * Gunakan satu record sebagai record utama.
                     */
                    $mainRecord = $records->first();

                    /*
                     * Kalau soft deleted, restore.
                     */
                    if ($mainRecord->trashed()) {
                        $mainRecord->restore();
                    }

                    /*
                     * Hapus permanen duplikat.
                     */
                    foreach ($records->skip(1) as $duplicate) {
                        $duplicate->forceDelete();
                    }

                } else {

                    /*
                     * Kalau belum pernah ada, buat baru.
                     */
                    SchoolMapel::create([
                        'school_id' => $school->id,
                        'master_mapel_id' => $mapelId,
                    ]);
                }
            }


            /*
             * 2. Jurusan yang tidak dicentang → soft delete.
             */
            $activeRecords = SchoolMapel::where('school_id', $school->id)
                ->get();

            foreach ($activeRecords as $schoolMapel) {

                if (!$selectedIds->contains($schoolMapel->master_mapel_id)) {
                    $schoolMapel->delete();
                }
            }
        });

        return redirect()
            ->route('school_mapel.index')
            ->with('success', 'Mata pelajaran sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolMapel $schoolMapel)
    {
        $school = $this->getSchool();

        // Pastikan mapel memang milik sekolah admin
        if ($schoolMapel->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $schoolMapel->delete();

        return redirect()->route('school_mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
