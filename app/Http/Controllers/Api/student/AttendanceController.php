<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Rombel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun siswa.'
            ], 403);
        }

        $query = Attendance::with('rombel')->where('student_id', $student->id);

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        $attendances = $query->latest('tanggal')->latest('jam')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kehadiran berhasil diambil.',
            'data' => $attendances,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan akun siswa.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'qr_code' => 'required_if:status,hadir|nullable|string',
            'status' => 'required|in:hadir,izin,sakit',
            'lampiran' => 'required_if:status,izin,sakit|nullable|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek apakah siswa sudah presensi
        $tanggal = Carbon::today();

        $existingAttendance = Attendance::where('student_id',$student->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi hari ini.'
            ], 409);
        }

        // Pastikan siswa punya rombel
        if (!$student->rombel_id) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa belum memiliki rombel.'
            ], 403);
        }

        // Hadir
        $rombel = Rombel::find($student->rombel_id);

        if (!$rombel) {
            return response()->json([
                'success' => false,
                'message' => 'Rombel siswa tidak ditemukan.'
            ], 404);
        }
        
        $lampiran = null;

        if ($request->status === 'hadir') {
            if (!$rombel->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rombel sudah tidak aktif.'
                ], 403);
            }

            if ($request->qr_code !== $rombel->qr_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code bukan untuk kelas Anda.'
                ], 403);
            }
        }

        // Izin / sakit
        if (
            in_array($request->status, ['izin', 'sakit']) &&
            !$request->hasFile('lampiran')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Lampiran wajib diunggah.'
            ], 422);
        }

        if (in_array($request->status, ['izin','sakit'])) {
            if (!$request->hasFile('lampiran')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lampiran wajib diunggah.'
                ], 422);
            }

            $lampiran = $request->file('lampiran')
                ->store('attendance', 'public');
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'rombel_id' => $rombel->id,
            'tanggal' => $tanggal,
            'jam' => Carbon::now()->format('H:i:s'),
            'status' => $request->status,
            'lampiran' => $lampiran,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dikirim.',
        ], 201);
    }
}
