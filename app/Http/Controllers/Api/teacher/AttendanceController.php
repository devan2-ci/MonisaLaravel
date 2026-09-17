<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\TeacherSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun guru.',
            ], 403);
        }

        $now = Carbon::parse(now())->setTimezone('Asia/Jakarta');
        $time = $now->format('H:i');

        $hari = match ($now->dayOfWeek) {
            Carbon::MONDAY => 'senin',
            Carbon::TUESDAY => 'selasa',
            Carbon::WEDNESDAY => 'rabu',
            Carbon::THURSDAY => 'kamis',
            Carbon::FRIDAY => 'jumat',
            Carbon::SATURDAY => 'sabtu',
            Carbon::SUNDAY => 'minggu',
        };

        $schedule = TeacherSchedule::with([
            'rombel',
            'schoolMapel',
            'lessonPeriodStart',
            'lessonPeriodEnd',
        ])
            ->where('school_id', $teacher->school_id)
            ->where('teacher_id', $teacher->id)
            ->where('hari', $hari)
            ->where('is_active', true)
            ->whereHas('lessonPeriodStart', function ($query) use ($time) {
                $query->where('jam_mulai', '<=', $time);
            })
            ->whereHas('lessonPeriodEnd', function ($query) use ($time) {
                $query->where('jam_selesai', '>=', $time);
            })
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal mengajar pada saat ini.',
            ], 404);
        }
        
        $attendances = Attendance::where('rombel_id', $schedule->rombel_id)->whereDate('tanggal', $now->toDateString())->get()->keyBy('student_id');

        $students = $schedule->rombel->students()->orderBy('name')->get();

        $studentData = $students->map(function ($student) use ($attendances, $now) {

            $attendance = $attendances->get($student->id);

            return [
                'id' => $student->id,
                'name' => $student->name,
                'nis' => $student->nis,
                'nisn' => $student->nisn,
                'gender' => $student->gender,
                'photo' => $student->photo,

                'attendance' => $attendance ? [
                    'id' => $attendance->id,
                    'status' => $attendance->status,
                    'jam' => $attendance->jam,
                    'keterangan' => $attendance->keterangan,
                    'lampiran' => $attendance->lampiran,
                    
                ] : null,

                'sekarang' =>Carbon::parse(now())->setTimezone('Asia/Jakarta')->format('H:i')
                
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data presensi berhasil diambil.',
            'data' => [
                'schedule' => [
                    'id' => $schedule->id,
                    'hari' => $schedule->hari,

                    'mapel' => [
                        'id' => $schedule->schoolMapel->id,
                        'name' => $schedule->schoolMapel->name,
                    ],

                    'rombel' => [
                        'id' => $schedule->rombel->id,
                        'name' => $schedule->rombel->name,
                        'jenjang' => $schedule->rombel->jenjang,
                        'major' => $schedule->rombel->schoolMajor->major->kode_jur,
                    ],

                    'jam' => [
                        'mulai' => $schedule->lessonPeriodStart->jam_mulai,
                        'selesai' => $schedule->lessonPeriodEnd->jam_selesai,
                    ],
                ],

                'students' => $studentData,
            ],
        ]);
    }
}
