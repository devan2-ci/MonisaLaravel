<?php

namespace App\Http\Controllers\Api\teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherClass;
use App\Models\TeacherSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherClassController extends Controller
{
    /**
     * Gnerate code
     */
    private function generateJoinCode(): string
    {
        do {
            $code = 'MONISA-' . strtoupper(Str::random(6));
        } while (
            TeacherClass::where('join_code', $code)->exists()
        );

        return $code;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        $classes = TeacherClass::with(['rombel.schoolMajor.major', 'schoolMapel.masterMapel',])
            ->where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil.',
            'data' => $classes,
        ]);
    }

    /**
     * Option untuk jenjang, jurusan, nama kelas
     */
    public function option(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        $validated = $request->validate([
            'school_mapel_id' => 'required|integer|exists:school_mapels,id',
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
        ]);

        // Pastikan guru memang mengajar mapel tersebut
        $isTeacherOfMapel = $teacher->schoolMapels()->where('school_mapels.id', $validated['school_mapel_id'])->exists();

        if (!$isTeacherOfMapel) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak mengajar mata pelajaran tersebut.',
            ], 422);
        }

        // Ambil rombel berdasarkan jadwal guru
        $schedules = TeacherSchedule::with(['rombel.schoolMajor.major',])
            ->where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('school_mapel_id', $validated['school_mapel_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('is_active', true)
            ->whereHas('rombel', function ($query) {$query->where('is_active', true);})
            ->get();

        $rombels = $schedules->pluck('rombel')->filter()->unique('id')->values();

        // Format data untuk Flutter
        $data = $rombels->map(function ($rombel) {
            return [
                'id' => $rombel->id,
                'jenjang' => $rombel->jenjang,
                'jurusan_id' => $rombel->schoolMajor?->major_id,
                'jurusan' => $rombel->schoolMajor?->major?->name,
                'name' => $rombel->name,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Pilihan kelas berhasil diambil.',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        $validated = $request->validate([
            'rombel_id' => 'required|integer|exists:rombels,id',
            'school_mapel_id' => 'required|integer|exists:school_mapels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
        ]);

        $isTeacherOfMapel = $teacher->schoolMapels()->where('school_mapels.id', $validated['school_mapel_id'])->exists();

        if (!$isTeacherOfMapel) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak mengajar mata pelajaran tersebut.',
            ], 422);
        }

        $hasSchedule = TeacherSchedule::where('teacher_id', $teacher->id)
            ->where('rombel_id', $validated['rombel_id'])
            ->where('school_mapel_id', $validated['school_mapel_id'])
            ->where('is_active', true)
            ->exists();

        if (!$hasSchedule) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Guru tidak mengajar mata pelajaran tersebut pada rombel yang dipilih.',
            ], 422);
        }

        // Cek duplikat
        $exists = TeacherClass::where('teacher_id', $teacher->id)
            ->where('rombel_id', $validated['rombel_id'])
            ->where('school_mapel_id', $validated['school_mapel_id'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Kelas digital untuk rombel, mata pelajaran, dan tahun ajaran tersebut sudah ada.',
            ], 422);
        }

        $joinCode = $this->generateJoinCode();

        $teacherClass = TeacherClass::create([
            'school_id' => $teacher->school_id,
            'teacher_id' => $teacher->id,
            'rombel_id' => $validated['rombel_id'],
            'school_mapel_id' => $validated['school_mapel_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'join_code' => $joinCode,
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'is_active' => true,
        ]);

        $teacherClass->load([
            'rombel.schoolMajor.major',
            'schoolMapel.masterMapel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas digital berhasil dibuat.',
            'data' => $teacherClass,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, TeacherClass $class)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        if ($class->teacher_id !== $teacher->id || $class->school_id !== $teacher->school_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.',
            ], 403);
        }

        $class->load([
            'teacher',
            'rombel.schoolMajor.major',
            'schoolMapel.masterMapel',
            // 'students.user',
            // 'materials',
            // 'assignments',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail kelas berhasil diambil.',
            'data' => $class,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherClass $class)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        // Pastikan kelas milik guru
        if ($class->teacher_id !== $teacher->id || $class->school_id !== $teacher->school_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.',
            ], 403);
        }

        $validated = $request->validate([
            'rombel_id' => 'sometimes|required|integer|exists:rombels,id',
            'school_mapel_id' => 'sometimes|required|integer|exists:school_mapels,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'tahun_ajaran' => 'sometimes|required|string|regex:/^\d{4}\/\d{4}$/',
            'is_active' => 'sometimes|boolean',
        ]);

        $rombelId = $validated['rombel_id'] ?? $class->rombel_id;
        $schoolMapelId = $validated['school_mapel_id'] ?? $class->school_mapel_id;
        $tahunAjaran = $validated['tahun_ajaran'] ?? $class->tahun_ajaran;
        $isTeacherOfMapel = $teacher->schoolMapels()->where('school_mapels.id', $schoolMapelId)->exists();

        if (!$isTeacherOfMapel) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak mengajar mata pelajaran tersebut.',
            ], 422);
        }

        $hasSchedule = TeacherSchedule::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school_id)
            ->where('rombel_id', $rombelId)
            ->where('school_mapel_id', $schoolMapelId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('is_active', true)
            ->exists();

        if (!$hasSchedule) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Guru tidak mengajar mata pelajaran tersebut pada rombel yang dipilih.',
            ], 422);
        }

        // Cek duplikat
        $exists = TeacherClass::where('teacher_id', $teacher->id)
            ->where('rombel_id', $rombelId)
            ->where('school_mapel_id', $schoolMapelId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Kelas digital untuk rombel, mata pelajaran, dan tahun ajaran tersebut sudah ada.',
            ], 422);
        }

        $class->update([
            'rombel_id' => $validated['rombel_id'] ?? $class->rombel_id,
            'school_mapel_id' => $validated['school_mapel_id'] ?? $class->school_mapel_id,
            'name' => $validated['name'] ?? $class->name,
            'description' => array_key_exists('description', $validated) ? $validated['description'] : $class->description,
            'tahun_ajaran' => $validated['tahun_ajaran'] ?? $class->tahun_ajaran,
            'is_active' => $validated['is_active'] ?? $class->is_active,
        ]);

        $class->load([
            'rombel.schoolMajor.major',
            'schoolMapel.masterMapel',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas digital berhasil diperbarui.',
            'data' => $class,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TeacherClass $class)
    {
        $teacher = $request->user()->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data guru.',
            ], 403);
        }

        if ($class->teacher_id !== $teacher->id || $class->school_id !== $teacher->school_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.',
            ], 403);
        }

        $class->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas digital berhasil dihapus.',
        ]);
    }
}
