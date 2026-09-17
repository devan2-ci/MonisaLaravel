<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\SchoolMajor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchoolMajorController extends Controller
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

        $schoolMajors = SchoolMajor::with('major')
            ->where('school_id', $school->id)
            ->latest()
            ->get();

        return view('admin.school_majors.index', compact(
            'school',
            'schoolMajors'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = $this->getSchool();

        // Semua jurusan dari master SuperAdmin
        $majors = Major::orderBy('name')->get();

        // Jurusan yang sudah dimiliki sekolah
        $selectedMajorIds = SchoolMajor::where('school_id', $school->id)
            ->pluck('major_id')
            ->toArray();

        return view('admin.school_majors.create', compact(
            'school',
            'majors',
            'selectedMajorIds'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'major_ids' => 'nullable|array',
            'major_ids.*' => 'required|exists:majors,id',
        ]);

        $selectedIds = collect($validated['major_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        DB::transaction(function () use ($school, $selectedIds) {

            /*
             * Ambil semua data, termasuk yang sudah soft delete.
             */
            $existing = SchoolMajor::withTrashed()
                ->where('school_id', $school->id)
                ->get()
                ->groupBy('major_id');


            /*
             * Sinkronisasi jurusan.
             */
            foreach ($selectedIds as $majorId) {

                /*
                 * Kalau sudah ada record dengan major_id tersebut,
                 * gunakan satu record saja.
                 */
                if (isset($existing[$majorId])) {

                    $records = $existing[$majorId];

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
                    SchoolMajor::create([
                        'school_id' => $school->id,
                        'major_id' => $majorId,
                    ]);
                }
            }


            /*
             * Jurusan yang tidak dipilih akan di-soft delete.
             */
            $current = SchoolMajor::where('school_id', $school->id)
                ->get();

            foreach ($current as $schoolMajor) {

                if (!$selectedIds->contains($schoolMajor->major_id)) {
                    $schoolMajor->delete();
                }
            }
        });

        return redirect()
            ->route('school_majors.index')
            ->with('success', 'Jurusan sekolah berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(SchoolMajor $schoolMajor)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * Tidak membutuhkan ID school_major karena yang diedit
     * adalah seluruh jurusan milik sekolah admin.
     */
    public function edit()
    {
        $school = $this->getSchool();

        /*
         * Ambil SEMUA jurusan dari master SuperAdmin.
         */
        $majors = Major::orderBy('name')->get();

        /*
         * Ambil ID jurusan yang sedang aktif di sekolah.
         */
        $selectedMajorIds = SchoolMajor::where('school_id', $school->id)
            ->pluck('major_id')
            ->toArray();

        return view('admin.school_majors.edit', compact(
            'school',
            'majors',
            'selectedMajorIds'
        ));
    }


    /**
     * Update jurusan sekolah.
     */
    public function update(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'major_ids' => 'nullable|array',
            'major_ids.*' => 'required|exists:majors,id',
        ]);

        $selectedIds = collect($validated['major_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        DB::transaction(function () use ($school, $selectedIds) {

            /*
             * Ambil SEMUA record school_major,
             * termasuk yang sudah soft delete.
             */
            $existing = SchoolMajor::withTrashed()
                ->where('school_id', $school->id)
                ->get()
                ->groupBy('major_id');


            /*
             * 1. Aktifkan jurusan yang dicentang.
             */
            foreach ($selectedIds as $majorId) {

                if (isset($existing[$majorId])) {

                    $records = $existing[$majorId];

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
                    SchoolMajor::create([
                        'school_id' => $school->id,
                        'major_id' => $majorId,
                    ]);
                }
            }


            /*
             * 2. Jurusan yang tidak dicentang → soft delete.
             */
            $activeRecords = SchoolMajor::where('school_id', $school->id)
                ->get();

            foreach ($activeRecords as $schoolMajor) {

                if (!$selectedIds->contains($schoolMajor->major_id)) {
                    $schoolMajor->delete();
                }
            }
        });

        return redirect()
            ->route('school_majors.index')
            ->with('success', 'Jurusan sekolah berhasil diperbarui.');
    }


    /**
     * Hapus satu jurusan.
     */
    public function destroy(SchoolMajor $schoolMajor)
    {
        $school = $this->getSchool();

        /*
         * Pastikan record benar-benar milik sekolah admin.
         */
        if ($schoolMajor->school_id !== $school->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $schoolMajor->delete();

        return redirect()
            ->route('school_majors.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}