<?php

namespace App\Http\Controllers\Api\parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentHomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $guardian = $user->guardian;

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Data orang tua tidak ditemukan.',
            ], 404);
        }

        $studentId = $guardian->student_id;

        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);

        // Presensi bulan yang dipilih
        $attendances = Attendance::with('rombel')
            ->where('student_id', $studentId)
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
            ->where('student_id', $studentId)
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
                'lampiran' => $latestAttendance->lampiran ? asset('storage/' . $latestAttendance->lampiran) : null,
                'rombel' => $latestAttendance->rombel
                    ? [
                        'id' => $latestAttendance->rombel->id,
                        'nama' => $latestAttendance->rombel->name,
                    ]
                    : null,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Data beranda orang tua berhasil diambil.',
            'data' => [
                'orang_tua' => [
                    'id' => $user->id,
                    'nama' => $user->name,
                ],

                'siswa' => [
                    'id' => $guardian->student_id,
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
