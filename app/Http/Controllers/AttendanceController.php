<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rombel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
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
        
        $attendances = Attendance::with(['student', 'rombel'])
            ->whereHas('student', function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->whereHas('rombel', function ($query) use ($school) {
                $query->where('school_id', $school->id);
            })
            ->latest('tanggal')->latest('jam')->paginate(10);

        return view('admin.attendances.index', compact('attendances','school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = $this->getSchool();

        $students = Student::where('school_id', $school->id)->orderBy('name')->get();
        $rombels = Rombel::where('school_id', $school->id)->where('is_active', true)->orderBy('jenjang')->orderBy('name')->get();

        return view('admin.attendances.create', compact('students','school','rombels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'lampiran' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $student = Student::where('id', $validated['student_id'])->where('school_id', $school->id)->first();

        if (!$student) {
            abort(403, 'Siswa tidak berasal dari sekolah Anda.');
        }

        $rombel = Rombel::where('id', $student->rombel_id)->where('school_id', $school->id)->first();

        if (!$rombel) {
            abort(403, 'Rombel tidak berasal dari sekolah Anda.');
        }

        if ((int) $student->rombel_id !== (int) $rombel->id) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Siswa tidak terdaftar pada rombel tersebut.'
                ]);
        }

        $exists = Attendance::where('student_id', $student->id)->whereDate('tanggal', $validated['tanggal'])->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'tanggal' => 'Siswa tersebut sudah memiliki data presensi pada tanggal tersebut.'
                ]);
        }

        if (
            in_array($validated['status'], ['izin', 'sakit']) &&
            !$request->hasFile('lampiran')
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'lampiran' => 'Lampiran wajib diunggah untuk status izin atau sakit.'
                ]);
        }

        $lampiran = null;

        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran')
                ->store('attendance', 'public');
        }

        Attendance::create([
            'student_id' => $student->id,
            'rombel_id' => $student->rombel_id,
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'status' => $validated['status'],
            'lampiran' => $lampiran,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Data presensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $school = $this->getSchool();

        $attendance->load(['student','rombel']);

        if (
            $attendance->student->school_id != $school->id ||
            $attendance->rombel->school_id != $school->id
        ) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data presensi ini.');
        }

        $students = Student::where('school_id', $school->id)->orderBy('name')->get();

        $rombels = Rombel::where('school_id', $school->id)->where('is_active', true)->orderBy('jenjang')->orderBy('name')->get();

        return view('admin.attendances.edit', compact('attendance','students','rombels','school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $school = $this->getSchool();

        $attendance->load([
            'student',
            'rombel'
        ]);

        if (
            $attendance->student->school_id != $school->id ||
            $attendance->rombel->school_id != $school->id
        ) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data presensi ini.');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tanggal' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'lampiran' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $student = Student::where('id', $validated['student_id'])->where('school_id', $school->id)->first();

        if (!$student) {
            abort(403, 'Siswa tidak berasal dari sekolah Anda.');
        }

        $rombel = Rombel::where('id', $student->rombel_id)->where('school_id', $school->id)->first();

        if (!$rombel) {
            abort(403, 'Rombel tidak berasal dari sekolah Anda.');
        }

        if ((int) $student->rombel_id !== (int) $rombel->id) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Siswa tidak terdaftar pada rombel tersebut.'
                ]);
        }

        $exists = Attendance::where('student_id', $student->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('id', '!=', $attendance->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'tanggal' => 'Siswa tersebut sudah memiliki data presensi pada tanggal tersebut.'
                ]);
        }

        if (
            in_array($validated['status'], ['izin', 'sakit']) &&
            !$request->hasFile('lampiran') &&
            !$attendance->lampiran
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'lampiran' => 'Lampiran wajib diunggah untuk status izin atau sakit.'
                ]);
        }

        $lampiran = $attendance->lampiran;

        if ($request->hasFile('lampiran')) {

            // Hapus file lama
            if ($attendance->lampiran) {
                Storage::disk('public')->delete(
                    $attendance->lampiran
                );
            }

            // Simpan file baru
            $lampiran = $request->file('lampiran')
                ->store('attendance', 'public');
        }

        $attendance->update([
            'student_id' => $student->id,
            'rombel_id' => $student->rombel_id,
            'tanggal' => $validated['tanggal'],
            'jam' => $validated['jam'],
            'status' => $validated['status'],
            'lampiran' => $lampiran,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('attendances.index')->with('success', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $school = $this->getSchool();

        $attendance->load([
            'student',
            'rombel'
        ]);

        if (
            $attendance->student->school_id != $school->id ||
            $attendance->rombel->school_id != $school->id
        ) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data presensi ini.');
        }

        if ($attendance->lampiran) {
            Storage::disk('public')->delete(
                $attendance->lampiran
            );
        }

        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Data presensi berhasil dihapus.');
    }
}
