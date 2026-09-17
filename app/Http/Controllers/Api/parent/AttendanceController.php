<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Cek autentikasi
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        // Ambil data wali dari user yang sedang login
        $guardian = $user->guardian;

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun wali.',
            ], 403);
        }

        // Pastikan wali memiliki siswa
        if (!$guardian->student_id) {
            return response()->json([
                'success' => false,
                'message' => 'Wali belum memiliki siswa yang diwakili.',
            ], 403);
        }

        // Query presensi berdasarkan siswa yang dimiliki wali
        $query = Attendance::with('rombel')
            ->where('student_id', $guardian->student_id);

        // Filter tahun
        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        // Filter bulan
        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        $attendances = $query
            ->latest('tanggal')
            ->latest('jam')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data presensi siswa berhasil diambil.',
            'data' => $attendances,
        ]);
    }

    public function latest(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $guardian = $user->guardian;

        if (!$guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun wali.',
            ], 403);
        }

        if (!$guardian->student_id) {
            return response()->json([
                'success' => false,
                'message' => 'Wali belum memiliki siswa yang diwakili.',
            ], 403);
        }

        $attendance = Attendance::with('rombel')->where('student_id', $guardian->student_id)->latest('tanggal')->latest('jam')->first();

        if (!$attendance) {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada data presensi.',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Presensi terbaru berhasil diambil.',
            'data' => $attendance,
        ]);
    }
}
