<?php

namespace App\Http\Controllers\Api\teacher;

use App\Http\Controllers\Controller;
use App\Models\LessonPeriod;
use App\Models\TeacherSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 404);
        }

        $today = Carbon::now();

        $hariMap = [
            'Monday'    => 'senin',
            'Tuesday'   => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday'  => 'kamis',
            'Friday'    => 'jumat',
            'Saturday'  => 'sabtu',
            'Sunday'    => 'minggu',
        ];

        $hari = $hariMap[$today->format('l')];

        $schedules = TeacherSchedule::with([
            'rombel',
            'schoolMapel.masterMapel',
            'lessonPeriodStart',
            'lessonPeriodEnd',
        ])
            ->where('teacher_id', $teacher->id)
            ->where('hari', $hari)
            ->get()
            ->sortBy(function ($schedule) {
                return $schedule->lessonPeriodStart?->mulai;
            })
            ->values();

        $jadwal = collect();
        foreach ($schedules as $schedule) {
            $start = $schedule->lessonPeriodStart;
            $end = $schedule->lessonPeriodEnd;

            if (!$start || !$end) {
                continue;
            }

            // Ambil semua JP di antara start dan end
            $periods = LessonPeriod::where('school_id', $teacher->school_id)
                ->whereBetween('jam_ke', [
                    $start->jam_ke,
                    $end->jam_ke,
                ])
                ->where('is_active', true)
                ->orderBy('jam_ke')
                ->get();

            foreach ($periods as $period) {
                $jadwal->push([
                    'id' => $schedule->id,
                    'jam' => Carbon::parse($period->jam_mulai)->format('H:i')
                        . '–' .
                        Carbon::parse($period->jam_selesai)->format('H:i'),
                    'jam_ke' => $period->jam_ke,
                    'jenjang' => $schedule->rombel?->jenjang,
                    'jurusan' => $schedule->rombel?->schoolMajor->major->kode_jur,
                    'name' => $schedule->rombel?->name,
                    'mata_pelajaran' => $schedule->schoolMapel?->masterMapel?->name,
                ]);
            }
        };

        return response()->json([
            'success' => true,
            'message' => 'Data beranda guru berhasil diambil.',
            'data' => [
                'guru' => [
                    'id' => $teacher->id,
                    'nama' => $teacher->name,
                ],
                'hari' => $hari,
                'tanggal' => $today->format('Y-m-d'),
                'jadwal_mengajar' => $jadwal,
            ],
        ]);
    }
}
