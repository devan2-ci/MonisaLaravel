<?php

namespace App\Http\Controllers;

use App\Models\LessonPeriod;
use App\Models\Rombel;
use App\Models\SchoolMapel;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleController extends Controller
{
    private function getSchool()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        return $admin->school;
    }

    private function validateSchoolRelation($schoolId, array $validated)
    {
        $teacher = Teacher::where('id', $validated['teacher_id'])
            ->where('school_id', $schoolId)
            ->first();

        $rombel = Rombel::where('id', $validated['rombel_id'])
            ->where('school_id', $schoolId)
            ->first();

        if (!$teacher || !$rombel ) {
            abort(403, 'Data guru, rombel, atau mata pelajaran tidak sesuai dengan sekolah.');
        }

        return $teacher;
    }

    private function hasScheduleConflict($schoolId, array $validated, $excludeId = null) 
    {
        $query = TeacherSchedule::where('school_id', $schoolId)
            ->where('hari', $validated['hari'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('lesson_period_start_id', [
                    $validated['lesson_period_start_id'],
                    $validated['lesson_period_end_id']
                ])
                ->orWhereBetween('lesson_period_end_id', [
                    $validated['lesson_period_start_id'],
                    $validated['lesson_period_end_id']
                ])
                ->orWhere(function ($query) use ($validated) {
                    $query->where(
                        'lesson_period_start_id',
                        '<=',
                        $validated['lesson_period_start_id']
                    )
                    ->where(
                        'lesson_period_end_id',
                        '>=',
                        $validated['lesson_period_end_id']
                    );
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->get();

        foreach ($conflicts as $conflict) {
            // Guru yang sama tidak boleh mengajar di dua kelas
            // pada jam yang bertabrakan.
            if ((int) $conflict->teacher_id === (int) $validated['teacher_id']) {
                return true;
            }

            // Rombel yang sama tidak boleh memiliki dua guru
            // pada jam yang bertabrakan.
            if ((int) $conflict->rombel_id === (int) $validated['rombel_id']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = $this->getSchool();

        $schedules = TeacherSchedule::with([
            'teacher',
            'rombel',
            'schoolMapel',
            'lessonPeriodStart',
            'lessonPeriodEnd',
        ])
            ->where('school_id', $school->id)
            ->orderByRaw("
                FIELD(
                    hari,
                    'senin',
                    'selasa',
                    'rabu',
                    'kamis',
                    'jumat',
                    'sabtu'
                )
            ")
            ->orderBy('lesson_period_start_id')
            ->get();

        return view('admin.teacher_schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $school = $this->getSchool();

        $teachers = Teacher::where('school_id', $school->id)->with('schoolMapel')->orderBy('name')->get();

        $rombels = Rombel::where('school_id', $school->id)->where('is_active', true)->orderBy('jenjang')->orderBy('name')->get();

        $lessonPeriods = LessonPeriod::where('school_id', $school->id)->where('is_active', true)->orderBy('jam_ke')->get();

        $hari = [
            'senin',
            'selasa',
            'rabu',
            'kamis',
            'jumat',
            'sabtu',
        ];

        return view('admin.teacher_schedules.create', compact('teachers','rombels','lessonPeriods','hari'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'rombel_id' => 'required|exists:rombels,id',
            'lesson_period_start_id' => 'required|exists:lesson_periods,id',
            'lesson_period_end_id' => 'required|exists:lesson_periods,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'is_active' => 'nullable|boolean',
        ]);

        $teacher = $this->validateSchoolRelation(
            $school->id,
            $validated
        );

        $startPeriod = LessonPeriod::where('school_id', $school->id)->findOrFail($validated['lesson_period_start_id']);

        $endPeriod = LessonPeriod::where('school_id', $school->id)->findOrFail($validated['lesson_period_end_id']);

        if ($startPeriod->jam_ke > $endPeriod->jam_ke) {
            return back()
                ->withInput()
                ->withErrors([
                    'lesson_period_end_id' =>
                        'Jam selesai tidak boleh lebih awal dari jam mulai.'
                ]);
        }

        if ($this->hasScheduleConflict(
            $school->id,
            $validated,
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'lesson_period_start_id' =>
                        'Jadwal bentrok dengan jadwal yang sudah ada.'
                ]);
        }

        TeacherSchedule::create([
            'school_id' => $school->id,
            'teacher_id' => $validated['teacher_id'],
            'rombel_id' => $validated['rombel_id'],
            'school_mapel_id' => $teacher->school_mapel_id,
            'lesson_period_start_id' => $validated['lesson_period_start_id'],
            'lesson_period_end_id' => $validated['lesson_period_end_id'],
            'hari' => $validated['hari'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('teacher-schedules.index')->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherSchedule $teacherSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherSchedule $teacherSchedule)
    {
        $school = $this->getSchool();

        if ($teacherSchedule->school_id != $school->id) {
            abort(404);
        }

        $teachers = Teacher::where('school_id', $school->id)->with('schoolMapel')->orderBy('name')->get();

        $rombels = Rombel::where('school_id', $school->id)->where('is_active', true)->orderBy('jenjang')->orderBy('name')->get();

        $lessonPeriods = LessonPeriod::where('school_id', $school->id)->where('is_active', true)->orderBy('jam_ke')->get();

        $hari = [
            'senin',
            'selasa',
            'rabu',
            'kamis',
            'jumat',
            'sabtu',
        ];

        return view('admin.teacher_schedules.edit',compact('teacherSchedule','teachers','rombels','lessonPeriods','hari'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherSchedule $teacherSchedule)
    {
        $school = $this->getSchool();

        if ($teacherSchedule->school_id != $school->id) {
            abort(404);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'rombel_id' => 'required|exists:rombels,id',
            'lesson_period_start_id' => 'required|exists:lesson_periods,id',
            'lesson_period_end_id' => 'required|exists:lesson_periods,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'is_active' => 'nullable|boolean',
        ]);

        $teacher = $this->validateSchoolRelation(
            $school->id,
            $validated
        );

        $startPeriod = LessonPeriod::where('school_id', $school->id)->findOrFail($validated['lesson_period_start_id']);

        $endPeriod = LessonPeriod::where('school_id', $school->id)->findOrFail($validated['lesson_period_end_id']);

        if ($startPeriod->jam_ke > $endPeriod->jam_ke) {
            return back()
                ->withInput()
                ->withErrors([
                    'lesson_period_end_id' =>
                        'Jam selesai tidak boleh lebih awal dari jam mulai.'
                ]);
        } 

        if ($this->hasScheduleConflict(
            $school->id,
            $validated,
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'lesson_period_start_id' =>
                        'Jadwal bentrok dengan jadwal yang sudah ada.'
                ]);
        }

        $teacherSchedule->update([
            'teacher_id' => $validated['teacher_id'],
            'rombel_id' => $validated['rombel_id'],
            'school_mapel_id' => $teacher->school_mapel_id,
            'lesson_period_start_id' =>$validated['lesson_period_start_id'],
            'lesson_period_end_id' =>$validated['lesson_period_end_id'],
            'hari' => $validated['hari'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('teacher-schedules.index')->with('success', 'Jadwal mengajar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherSchedule $teacherSchedule)
    {
        $school = $this->getSchool();

        if ($teacherSchedule->school_id != $school->id) {
            abort(404);
        }
        
        $teacherSchedule->delete();

        return redirect()->route('teacher-schedules.index')->with('success', 'Jadwal mengajar berhasil dihapus.');
    }

}
