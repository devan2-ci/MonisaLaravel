<?php

namespace App\Http\Controllers;
use App\Models\LessonPeriod;
use App\Models\Rombel;
use App\Models\SchoolMapel;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    private function getSchool()
    {
        $user = Auth::user();

        if (!$user || !$user->admin || !$user->admin->school){
            abort(403, 'Admin belum terhubung dengan sekolah.');
        }

        return $user->admin->school;
    }

    // Hari

    private function days()
    {
        return [
            'senin',
            'selasa',
            'rabu',
            'kamis',
            'jumat',
            'sabtu',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = $this->getSchool();

        $teachers = Teacher::with(['user','schoolMapels.masterMapel',])->where('school_id', $school->id)->latest()->paginate(10);

        return view('admin.teachers.index',compact('school','teachers'));
    }

    // Create - Step 1
    public function create()
    {
        $school = $this->getSchool();

        $schoolMapels = SchoolMapel::with('masterMapel')->where('school_id', $school->id)->orderBy('id')->get();

        // Ambil data lama jika user kembali ke Step 1
        $teacherData = session('teacher_create.step1',[]);

        // Ambil mapel yang sebelumnya dipilih
        $selectedMapelIds = $teacherData['mapel_ids'] ?? [];

        return view('admin.teachers.create',compact('school','schoolMapels','teacherData','selectedMapelIds'));
    }

    // Store Step 1
    public function storeStep1(Request $request)
    {
        $school = $this->getSchool();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:30|unique:teachers,nip',
            'nuptk' => 'required|string|max:30|unique:teachers,nuptk',
            'email' => 'required|string|max:255|unique:teachers,email',
            'gender' => 'required|in:l,p',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20|unique:students,no_hp',
            'mapel_ids' => 'required|array|min:1',
            'mapel_ids.*' => 'required|integer',
        ]);

        // Hapus duplikat mapel
        $validated['mapel_ids'] = array_values(
            array_unique(
                array_map(
                    'intval',
                    $validated['mapel_ids']
                )
            )
        );

        // Simpan ke SESSION
        session()->put(
            'teacher_create.step1',
            $validated
        );

        // Masuk Step 2
        return redirect()->route('teachers.create.step2')->with('success','Data guru berhasil disimpan sementara.');
    }

    // Create - Step 2
    public function createStep2()
    {
        $school = $this->getSchool();

        // Ambil data guru dari SESSION
        $teacherData = session('teacher_create.step1');

        // Kalau tidak ada session, kembali ke Step 1
        if (!$teacherData) {
            return redirect()
                ->route('teachers.create')
                ->with(
                    'error',
                    'Silakan isi data guru terlebih dahulu.'
                );
        }

        // Ambil mapel pilihan Step 1
        $selectedMapelIds = $teacherData['mapel_ids'] ?? [];
        $selectedMapelIds = array_values(
            array_unique(
                array_map(
                    'intval',
                    (array) $selectedMapelIds
                )
            )
        );

        // Ambil mapel milik sekolah
        $schoolMapels = SchoolMapel::with(['masterMapel',])->where('school_id',$school->id)->whereIn('id',$selectedMapelIds)->get();

        // Ambil semua ROMBEL sekolah
        $rombels = Rombel::with(['schoolMajor.major',])
            ->where('school_id',$school->id)
            ->where('is_active',true)
            ->orderBy('jenjang')
            ->orderBy('name')
            ->get();

        // Ambil assignment yang pernah disimpan
        $assignments = session('teacher_create.step2',[]);

        return view('admin.teachers.create-step2',compact('school','teacherData','schoolMapels','rombels','selectedMapelIds','assignments'));
    }

    // Store Step 2
    public function storeStep2(Request $request)
    {
        $school = $this->getSchool();

        // Pastikan Step 1 ada
        $teacherData = session('teacher_create.step1');

        if (!$teacherData) {
            return redirect()
                ->route('teachers.create')
                ->with(
                    'error','Session data guru tidak ditemukan.'
                );
        }

        $request->validate([
            'assignments' => 'required|array|min:1',
        ]);

        $assignmentsInput =$request->input('assignments',[]);

        // Ambil mapel yang dipilih di Step 1
        $selectedMapelIds = $teacherData['mapel_ids'] ?? [];
        $selectedMapelIds = array_values(
            array_unique(
                array_map(
                    'intval',
                    (array) $selectedMapelIds
                )
            )
        );

        // Ambil mapel yang benar-benar milik sekolah
        $validMapelIds = SchoolMapel::where('school_id',$school->id)
            ->whereIn('id',$selectedMapelIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $normalizedAssignments = [];

        foreach ($assignmentsInput as $key => $value) {

            if (is_array($value) && !array_key_exists('school_mapel_id', $value)) {
                $schoolMapelId = (int) $key;

                $rombelIds = array_values(
                    array_unique(
                        array_map(
                            'intval',
                            (array) $value
                        )
                    )
                );

                if (!in_array($schoolMapelId,$validMapelIds)) {
                    continue;
                }

                $normalizedAssignments[] = [
                    'school_mapel_id' => $schoolMapelId,
                    'rombel_ids' => $rombelIds,
                ];

                continue;
            }

            if (is_array($value) && array_key_exists('school_mapel_id',$value)) {
                $schoolMapelId = (int) $value['school_mapel_id'];

                $rombelIds = array_values(
                    array_unique(
                        array_map(
                            'intval',
                            (array) (
                                $value['rombel_ids'] ?? []
                            )
                        )
                    )
                );

                if (!in_array($schoolMapelId, $validMapelIds)) {
                    continue;
                }

                $normalizedAssignments[] = [
                    'school_mapel_id' =>$schoolMapelId,
                    'rombel_ids' => $rombelIds,
                ];
            }
        }

        // Pastikan setiap mapel memiliki kelas
        foreach ($selectedMapelIds as $mapelId) {
            $found = collect($normalizedAssignments)->firstWhere('school_mapel_id', $mapelId);

            if (!$found) {
                throw ValidationException::withMessages([
                    'assignments' => 'Setiap mata pelajaran harus memiliki minimal satu kelas.',
                ]);
            }

            if (empty($found['rombel_ids'])) {
                throw ValidationException::withMessages([
                    'assignments' => 'Setiap mata pelajaran harus memiliki minimal satu kelas.',
                ]);
            }
        }

        // Semua ID rombel
        $allRombelIds = collect($normalizedAssignments)->pluck('rombel_ids')->flatten()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        // Pastikan rombel milik sekolah
        $validRombelIds = Rombel::where('school_id', $school->id)
            ->where('is_active',true)
            ->whereIn('id', $allRombelIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        // Pastikan tidak ada rombel ilegal
        foreach ($normalizedAssignments as $assignment) {
            foreach ($assignment['rombel_ids'] as $rombelId) {
                if (!in_array($rombelId, $validRombelIds)) {
                    throw ValidationException::withMessages([
                        'assignments' => 'Terdapat kelas yang tidak valid.',
                    ]);
                }
            }
        }

        // Simpan Step 2 ke session
        session()->put('teacher_create.step2', $normalizedAssignments);

        return redirect()->route('teachers.create.step3')->with('success', 'Kelas berhasil dipilih.');
    }

    // Create guru - Step 3
    public function createStep3()
    {
        // Ambil admin yang sedang login
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school_id) {
            return redirect()->route('teachers.index')->with('error', 'Data sekolah admin tidak ditemukan.');

        }

        $schoolId = $admin->school_id;

        // Ambil data Step 1
        $step1 = session('teacher_create.step1');

        if (!$step1) {
            return redirect()->route('teachers.create')->with('error', 'Silakan lengkapi Langkah 1 terlebih dahulu.');
        }

        // Ambil data Step 2
        $step2 = session('teacher_create.step2');

        if (!$step2) {
            return redirect()->route('teachers.create.step2')->with('error', 'Silakan tentukan kelas yang akan diajar terlebih dahulu.');
        }

        // MAPEL DARI STEP 1
        $mapelIds = $step1['mapel_ids'] ?? [];

        if (empty($mapelIds)) {
            return redirect()
                ->route('teachers.create')
                ->with(
                    'error',
                    'Mata pelajaran guru belum ditentukan.'
                );
        }

        // Ambil SchoolMapel
        $schoolMapels = SchoolMapel::with('masterMapel')->where('school_id', $schoolId)->whereIn('id', $mapelIds)->get();

        // Ambil ROMBEL dari STEP 2
        $rombelsByMapel = [];

        foreach ($step2 as $item) {
            $schoolMapelId = $item['school_mapel_id'] ?? null;
            $rombelIds = $item['rombel_ids'] ?? [];

            if (!$schoolMapelId) {
                continue;
            }

            if (empty($rombelIds)) {
                $rombelsByMapel[$schoolMapelId] = collect();

                continue;
            }

            $rombels = Rombel::with(['schoolMajor.major',])
                ->where('school_id', $schoolId)
                ->whereIn('id', $rombelIds)
                ->where('is_active', true)
                ->get();

            $rombelsByMapel[$schoolMapelId] = $rombels;
        }

        // Jam pelajaran
        $lessonPeriods = LessonPeriod::where('school_id', $schoolId)->where('is_active', true)->orderBy('jam_ke')->get();

        return view('admin.teachers.create-step3',compact('schoolMapels', 'rombelsByMapel', 'lessonPeriods'));
    }

    // STORE GURU - STEP 3
    public function storeStep3(Request $request)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $schoolId = $admin->school_id;

        // Ambil session step 1
        $step1 = session('teacher_create.step1');

        if (!$step1) {
            return redirect()
                ->route('teachers.create')
                ->with('error', 'Data Langkah 1 tidak ditemukan.');
        }

        // Ambil session step 2
        $step2 = session('teacher_create.step2');

        if (!$step2) {
            return redirect()
                ->route('teachers.create.step2')
                ->with('error', 'Data Langkah 2 tidak ditemukan.');
        }

        // Validasi JADWAL
        $validated = $request->validate([
            'schedules' => 'required|array|min:1',
            'schedules.*' => 'required|array',
            'schedules.*.*' => 'required|array',
            'schedules.*.*.*' => 'required|array',
            'schedules.*.*.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'schedules.*.*.*.lesson_period_start_id' => 'required|integer',
            'schedules.*.*.*.lesson_period_end_id' => 'required|integer',
        ]);

        // MAPEL YANG DIPILIH STEP 1
        $mapelIds = $step1['mapel_ids'] ?? [];

        if (empty($mapelIds)) {
            return redirect()
                ->route('teachers.create')
                ->with('error', 'Mata pelajaran belum dipilih.');
        }

        DB::beginTransaction();

        try {
            // Buat User
            $user = User::create([
                'name' => $step1['name'],
                'email' => $step1['email'] ?? null,
                'username' => $step1['nip'],
                'password' => Hash::make($step1['nip']),
                'phone' => $step1['no_hp'] ?? null,
            ]);

            $user->assignRole('teacher');

            // Buat guru
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'nip' => $step1['nip'],
                'nuptk' => $step1['nuptk'] ?? null,
                'name' => $step1['name'],
                'email' => $step1['email'] ?? null,
                'gender' => $step1['gender'] ?? null,
                'tanggal_lahir' => $step1['tanggal_lahir'] ?? null,
                'alamat' => $step1['alamat'] ?? null,
                'no_hp' => $step1['no_hp'] ?? null,
            ]);

            // Hubungkan guru dengan mapel
            $teacher->schoolMapels()->sync($mapelIds);

            // Simpan jadwal
            foreach ($validated['schedules'] as $schoolMapelId => $rombels) {
                
                // Pastikan mapel milik sekolah
                $schoolMapel = SchoolMapel::where('school_id', $schoolId)->where('id', $schoolMapelId)->first();

                if (!$schoolMapel) {
                    continue;
                }

                // Pastikan mapel dipilih di step 1
                if (
                    !in_array(
                        (int) $schoolMapelId,
                        array_map(
                            'intval',
                            $mapelIds
                        ),
                        true
                    )
                ) {
                    continue;
                }

                // Loop ROMBEL
                foreach ($rombels as $rombelId => $schedules) {

                    // Pastikan ROMBEL milik SEKOLAH
                    $rombel = Rombel::where('school_id', $schoolId )->where('id', $rombelId)->where('is_active', true)->first();

                    if (!$rombel) {
                        continue;
                    }

                    // LOOP JADWAL kelas
                    foreach ($schedules as $schedule) {

                        // VALIDASI JAM MULAI
                        $startPeriod = LessonPeriod::where('school_id', $schoolId)->where('id', $schedule['lesson_period_start_id'])->where('is_active', true)->first();

                        // VALIDASI JAM SELESAI
                        $endPeriod = LessonPeriod::where('school_id', $schoolId)->where('id', $schedule['lesson_period_end_id'])->where('is_active', true)->first();

                        if (!$startPeriod || !$endPeriod) {
                            throw new \Exception(
                                'Jam pelajaran yang dipilih tidak valid.'
                            );
                        }

                        // VALIDASI URUTAN JAM
                        if ($startPeriod->jam_ke > $endPeriod->jam_ke) {
                            throw new \Exception(
                                'Jam mulai tidak boleh lebih besar dari jam selesai.'
                            );
                        }

                        // SIMPAN TEACHER SCHEDULE
                        TeacherSchedule::create([
                            'teacher_id' => $teacher->id,
                            'rombel_id' => $rombel->id,
                            'school_mapel_id' => $schoolMapel->id,
                            'lesson_period_start_id' => $startPeriod->id,
                            'lesson_period_end_id' => $endPeriod->id,
                            'hari' => $schedule['hari'],
                            'is_active' => true,
                        ]);
                    }
                }
            }

            DB::commit();

            // HAPUS SESSION WIZARD
            session()->forget('teacher_create');

            return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->withInput()->with('error','Gagal menyimpan data guru: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $schoolId = $admin->school_id;

        if ((int) $teacher->school_id !== (int) $schoolId) {
            abort(403, 'Guru tidak berada di sekolah Anda.');
        }

        // Ambil data guru
        $teacher->load([
            'user',
            'schoolMapels.masterMapel',
            'schedules.lessonPeriodStart',
            'schedules.lessonPeriodEnd',
            'schedules.rombel.schoolMajor.major',
        ]);

        // Ambil semua mata pelajaran sekolah
        $schoolMapels = SchoolMapel::with('masterMapel')
            ->where('school_id', $schoolId)
            ->orderBy('id')
            ->get();

        // Ambil semua rombel sekolah
        $rombels = Rombel::with(['schoolMajor.major',])
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('name')
            ->get();

        // Ambil jam pelajaran sekolah
        $lessonPeriods = LessonPeriod::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('jam_ke')
            ->get();

        // Susun data MAPEL + KELAS untuk STEP 2
        $editSubjects = $teacher->schoolMapels->map(function ($schoolMapel) use ($teacher) {
            // Ambil semua jadwal guru untuk mapel ini
            $rombelIds = $teacher->schedules->where('school_mapel_id', $schoolMapel->id)
                ->pluck('rombel_id')
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->toArray();

            return [
                'mapel' => $schoolMapel,
                'rombel_ids' => $rombelIds,
            ];
        })->values();

        // Data untuk JavaScript
        $rombelsData = $rombels->map(function ($rombel) {
            return [
                'id' => (int) $rombel->id,
                'name' =>
                    trim(
                        ($rombel->jenjang ?? '') . ' ' .
                        ($rombel->schoolMajor?->major?->name ?? '') . ' ' .
                        ($rombel->name ?? '')
                    ),
            ];
        })->values()->toArray();

        $lessonPeriodsData = $lessonPeriods->map(function ($period) {
            return [
                'id' => (int) $period->id,
                'jam_ke' => (int) $period->jam_ke,
                'jam_mulai' => substr((string) $period->jam_mulai,0,5),
                'jam_selesai' =>substr((string) $period->jam_selesai, 0,5),
            ];
        })->values()->toArray();

        // Hari
        $days = [
            'senin',
            'selasa',
            'rabu',
            'kamis',
            'jumat',
            'sabtu',
        ];

        $daysData = $days;

        return view('admin.teachers.edit', compact(
            'teacher',
            'schoolMapels',
            'rombels',
            'lessonPeriods',
            'editSubjects',
            'rombelsData',
            'lessonPeriodsData',
            'days',
            'daysData'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher) 
    {
        $school = $this->getSchool();

        if ($teacher->school_id !== $school->id) {
            abort(403);
        }

        // VALIDASI
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:teachers,nip,' . $teacher->id,
            'nuptk' => 'required|string|max:255|unique:teachers,nuptk,' . $teacher->id,
            'gender' => 'required|in:l,p',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'email' => 'required|string|max:255|unique:teachers,email,' . $teacher->id,
            'no_hp' => 'nullable|string|max:20|unique:teachers,no_hp,' . $teacher->id,
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|array',
            'subjects.*.school_mapel_id' => 'required|integer',
            'subjects.*.rombel_ids' => 'required|array|min:1',
            'subjects.*.rombel_ids.*' => 'required|integer',
            'schedules' => 'required|array|min:1',
            'schedules.*' => 'required|array',
            'schedules.*.school_mapel_id' => 'required|integer',
            'schedules.*.rombel_id' => 'required|integer',
            'schedules.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'schedules.*.lesson_period_start_id' => 'required|integer',
            'schedules.*.lesson_period_end_id' => 'required|integer',
        ]);

        // Cek mapel yang dipilih
        $selectedSubjects = [];

        foreach ($validated['subjects'] as $subject) {
            $schoolMapelId = (int) $subject['school_mapel_id'];

            // Cek mapel benar-benar milik sekolah admin
            $schoolMapel = SchoolMapel::where('id',$schoolMapelId)->where('school_id',$school->id)->first();

            if (!$schoolMapel) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'subjects' =>
                            'Mata pelajaran yang dipilih tidak valid.',
                    ]);
            }

            // Cegah mapel duplikat
            if (
                in_array(
                    $schoolMapelId,
                    $selectedSubjects,
                    true
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subjects' =>
                            'Mata pelajaran tidak boleh dipilih dua kali.',
                    ]);
            }


            $selectedSubjects[] = $schoolMapelId;
        }

        // SUSUN MAPEL => ROMBEL
        $subjectRombels = [];

        foreach ($validated['subjects'] as $subject) {
            $schoolMapelId = (int) $subject['school_mapel_id'];

            $rombelIds =
                array_map(
                    'intval',
                    $subject['rombel_ids']
                );


            /*
            | Pastikan semua rombel milik sekolah
            */

            $validRombelIds = Rombel::where(
                    'school_id',
                    $school->id
                )
                ->where(
                    'is_active',
                    true
                )
                ->whereIn(
                    'id',
                    $rombelIds
                )
                ->pluck('id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->toArray();


            /*
            | Kalau ada rombel yang tidak valid
            */

            if (
                count($validRombelIds)
                !==
                count(array_unique($rombelIds))
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subjects' =>
                            'Terdapat kelas yang tidak valid.',
                    ]);
            }

            $subjectRombels[$schoolMapelId] = array_unique($validRombelIds);
        }

        // VALIDASI JADWAL
        foreach ($validated['schedules'] as $schedule) {

            $schoolMapelId =
                (int) $schedule['school_mapel_id'];

            $rombelId =
                (int) $schedule['rombel_id'];


            /*
            | Mapel jadwal harus termasuk mapel yang dipilih
            */

            if (
                !in_array(
                    $schoolMapelId,
                    $selectedSubjects,
                    true
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedules' =>
                            'Terdapat jadwal dengan mata pelajaran yang tidak dipilih.',
                    ]);
            }


            /*
            | Rombel jadwal harus termasuk kelas
            | dari mapel tersebut
            */

            if (
                !isset(
                    $subjectRombels[$schoolMapelId]
                )
                ||
                !in_array(
                    $rombelId,
                    $subjectRombels[$schoolMapelId],
                    true
                )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedules' =>
                            'Kelas pada jadwal tidak sesuai dengan kelas yang dipilih untuk mata pelajaran tersebut.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi jam mulai
            |--------------------------------------------------------------------------
            */

            $startPeriod = LessonPeriod::where(
                    'school_id',
                    $school->id
                )
                ->where(
                    'id',
                    $schedule['lesson_period_start_id']
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();


            /*
            |--------------------------------------------------------------------------
            | Validasi jam selesai
            |--------------------------------------------------------------------------
            */

            $endPeriod = LessonPeriod::where(
                    'school_id',
                    $school->id
                )
                ->where(
                    'id',
                    $schedule['lesson_period_end_id']
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();


            if (!$startPeriod || !$endPeriod) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedules' =>
                            'Jam pelajaran yang dipilih tidak valid.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi urutan jam
            |--------------------------------------------------------------------------
            */

            if (
                $startPeriod->jam_ke
                >
                $endPeriod->jam_ke
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'schedules' =>
                            'Jam mulai tidak boleh lebih besar dari jam selesai.',
                    ]);
            }
        }

        DB::beginTransaction();

        try {
            $user = $teacher->user;
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['nip']),
                'username' => $validated['nip'],
                'phone' => $validated['no_hp'],
            ]);

            $teacher->update([
                'nip' => $validated['nip'],
                'nuptk' => $validated['nuptk'],
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'gender' => $validated['gender'],
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
            ]);

            // MAPEL
            $teacher->schoolMapels()->sync($selectedSubjects);

            // Hapus jadwal lama
            $teacher->schedules()->delete();

            // Buat jadwal baru
            foreach ($validated['schedules'] as $schedule) {
                TeacherSchedule::create([
                    'teacher_id' => $teacher->id,
                    'rombel_id' => (int) $schedule['rombel_id'],
                    'school_mapel_id' => (int) $schedule['school_mapel_id'],
                    'lesson_period_start_id' => (int) $schedule['lesson_period_start_id'],
                    'lesson_period_end_id' => (int) $schedule['lesson_period_end_id'],
                    'hari' => strtolower($schedule['hari']),
                    'is_active' => true,
                ]);
            }

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data guru: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher) 
    {
        $school = $this->getSchool();

        if ($teacher->school_id !== $school->id) {
            abort(403);
        }

        $teacher->schoolMapels()->detach();
        $teacher->schedules()->delete();
             
        // User
        $user = $teacher->user;
            
        // Teacher
        $teacher->delete();
            
        // User
        if ($user) {
            $user->delete();
        }


        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}