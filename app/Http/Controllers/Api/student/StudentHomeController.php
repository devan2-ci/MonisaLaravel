<?php

namespace App\Http\Controllers\Api\student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentHomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data orang tua tidak ditemukan.',
            ], 404);
        }

        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);

        // Presensi bulan yang dipilih
        $attendances = Attendance::with('rombel')
            ->where('student_id', $student->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->get();

        // Format kalender kehadiran
        $kalender = $attendances->map(function ($attendance) {
            return [
                'tanggal' => $attendance->tanggal,
                'status' => $attendance->status,
            ];
        })->values();

        // Presensi terbaru
        $latestAttendance = Attendance::with('rombel')
            ->where('student_id', $student->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->first();

        $presensiTerbaru = null;

        if ($latestAttendance) {
            $presensiTerbaru = [
                'tanggal' => $latestAttendance->tanggal,
                'status' => $latestAttendance->status,
                'jam' => $latestAttendance->jam,
                'keterangan' => $latestAttendance->keterangan,
                'lampiran' => $latestAttendance->lampiran,
                'rombel' => $latestAttendance->rombel
                    ? [
                        'id' => $latestAttendance->rombel->id,
                        'nama' => $latestAttendance->rombel->name,
                    ]
                    : null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Data beranda orang tua berhasil diambil.',
            'data' => [
                'siswa' => [
                    'id' => $student->id,
                    'nama' => $user->name,
                ],
                'periode' => [
                    'tahun' => $year,
                    'bulan' => $month,
                ],
                'kalender_kehadiran' => $kalender,
                'presensi_terbaru' => $presensiTerbaru,
            ],
        ]);
    }
}
