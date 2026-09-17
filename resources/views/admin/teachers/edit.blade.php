@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6 flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Edit Guru
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui data guru, mata pelajaran, kelas, dan jadwal mengajar.
            </p>

        </div>

        <a
            href="{{ route('teachers.index') }}"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
            Kembali
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">

            <div class="mb-2 font-semibold text-red-700">
                Terdapat kesalahan:
            </div>

            <ul class="list-disc pl-5 text-sm text-red-600">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    @if (session('error'))

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">

            <p class="text-sm text-red-600">
                {{ session('error') }}
            </p>

        </div>

    @endif


    @if (session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">

            <p class="text-sm text-green-600">
                {{ session('success') }}
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('teachers.update', $teacher) }}"
        method="POST"
        id="edit-teacher-form"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- STEP 1 - DATA GURU --}}
        {{-- ===================================================== --}}

        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">

            <div class="mb-6 border-b pb-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                        1
                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-800">
                            Data Guru
                        </h2>

                        <p class="text-sm text-gray-500">
                            Informasi pribadi guru
                        </p>

                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                {{-- Nama --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Guru
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $teacher->name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- NIP --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        NIP
                    </label>

                    <input
                        type="text"
                        name="nip"
                        value="{{ old('nip', $teacher->nip) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- NUPTK --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        NUPTK
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="nuptk"
                        value="{{ old('nuptk', $teacher->nuptk) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- Gender --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jenis Kelamin
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="gender"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                        <option value="">
                            Pilih
                        </option>

                        <option
                            value="l"
                            @selected(old('gender', $teacher->gender) === 'l')
                        >
                            Laki-laki
                        </option>

                        <option
                            value="p"
                            @selected(old('gender', $teacher->gender) === 'p')
                        >
                            Perempuan
                        </option>

                    </select>

                </div>


                {{-- Tanggal lahir --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $teacher->tanggal_lahir) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- No HP --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        No. Handphone
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp', $teacher->no_hp) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- Email --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        E-Mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $teacher->email) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >

                </div>


                {{-- Alamat --}}
                <div class="md:col-span-2">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >{{ old('alamat', $teacher->alamat) }}</textarea>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- STEP 2 - MAPEL & KELAS --}}
        {{-- ===================================================== --}}

        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">

            <div class="mb-6 flex items-center gap-3 border-b pb-4">

                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                    2
                </div>

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Mata Pelajaran & Kelas
                    </h2>

                    <p class="text-sm text-gray-500">
                        Atur mata pelajaran dan kelas yang diajar
                    </p>

                </div>

            </div>


            <div id="edit-subject-container">

                @foreach ($editSubjects as $index => $subject)

                    <div class="edit-subject-card mb-6 rounded-xl border border-gray-200 p-5">

                        <div class="mb-5 flex items-center justify-between">

                            <h3 class="font-semibold text-gray-800">
                                Mata Pelajaran
                            </h3>

                            <button
                                type="button"
                                onclick="removeEditSubject(this)"
                                class="text-sm font-medium text-red-500 hover:text-red-700"
                            >
                                Hapus
                            </button>

                        </div>


                        {{-- MAPEL --}}
                        <div class="mb-5">

                            <label class="mb-2 block text-sm font-medium text-gray-700">

                                Mata Pelajaran

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>

                            <select
                                name="subjects[{{ $index }}][school_mapel_id]"
                                class="edit-mapel-select w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                                required
                            >

                                <option value="">
                                    Pilih Mata Pelajaran
                                </option>

                                @foreach ($schoolMapels as $schoolMapel)

                                    <option
                                        value="{{ $schoolMapel->id }}"
                                        @selected(
                                            (int) $schoolMapel->id ===
                                            (int) $subject['mapel']->id
                                        )
                                    >
                                        {{ $schoolMapel->masterMapel?->name ?? 'Tanpa Nama' }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- SEARCH KELAS --}}
                        <div class="mb-4">

                            <input
                                type="text"
                                class="edit-rombel-search w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                                placeholder="Cari kelas..."
                                onkeyup="searchEditRombel(this)"
                            >

                        </div>


                        {{-- DAFTAR KELAS --}}
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">

                            @foreach ($rombels as $rombel)

                                @php

                                    $className =
                                        trim(
                                            $rombel->jenjang
                                            . ' '
                                            . ($rombel->schoolMajor?->major?->name ?? '')
                                            . ' '
                                            . $rombel->name
                                        );

                                @endphp

                                <label
                                    class="edit-rombel-item flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50"
                                    data-search="{{ strtolower($className) }}"
                                >

                                    <input
                                        type="checkbox"
                                        name="subjects[{{ $index }}][rombel_ids][]"
                                        value="{{ $rombel->id }}"
                                        @checked(
                                            in_array(
                                                $rombel->id,
                                                $subject['rombel_ids'] ?? []
                                            )
                                        )
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600"
                                    >

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $className }}
                                    </span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>


            <button
                type="button"
                onclick="addEditSubject()"
                class="rounded-lg border border-dashed border-blue-400 px-4 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50"
            >
                + Tambah Mata Pelajaran
            </button>

        </div>


        {{-- ===================================================== --}}
        {{-- STEP 3 - JADWAL --}}
        {{-- ===================================================== --}}

        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">

            <div class="mb-6 flex items-center gap-3 border-b pb-4">

                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                    3
                </div>

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Jadwal Mengajar
                    </h2>

                    <p class="text-sm text-gray-500">
                        Atur jadwal guru mengajar setiap kelas
                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- MAPEL --}}
            {{-- ================================================= --}}

            @foreach ($editSubjects as $subjectIndex => $subject)

                @php

                    $mapel = $subject['mapel'];

                    $subjectSchedules =
                        $teacher->schedules
                            ->where(
                                'school_mapel_id',
                                $mapel->id
                            )
                            ->values();

                    $groupedSchedules =
                        $subjectSchedules->groupBy('rombel_id');

                @endphp


                <div
                    class="edit-schedule-card mb-6 rounded-xl border border-gray-200"
                    data-mapel-id="{{ $mapel->id }}"
                >


                    {{-- HEADER MAPEL --}}
                    <div class="flex items-center justify-between border-b bg-gray-50 px-5 py-4">

                        <div class="flex items-center gap-3">

                            <span class="text-lg">
                                ▣
                            </span>

                            <h3 class="font-semibold text-gray-800">
                                Kelas -
                                {{ $mapel->masterMapel?->name ?? 'Tanpa Nama' }}
                            </h3>

                        </div>


                        {{-- TOMBOL TAMBAH --}}
                        <button
                            type="button"
                            onclick="toggleAddSchedulePanel({{ $mapel->id }})"
                            class="rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-50"
                        >
                            + Tambah
                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PANEL PILIH KELAS --}}
                    {{-- ================================================= --}}

                    <div
                        id="add-schedule-panel-{{ $mapel->id }}"
                        class="hidden border-b bg-white px-5 py-4"
                    >

                        <div class="flex flex-col gap-3 md:flex-row md:items-end">

                            <div class="flex-1">

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Pilih Kelas
                                </label>

                                <select
                                    id="add-rombel-{{ $mapel->id }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                                >

                                    <option value="">
                                        Pilih kelas
                                    </option>

                                    @foreach ($rombels as $rombel)

                                        @php

                                            $className =
                                                trim(
                                                    $rombel->jenjang
                                                    . ' '
                                                    . ($rombel->schoolMajor?->major?->name ?? '')
                                                    . ' '
                                                    . $rombel->name
                                                );

                                        @endphp

                                        <option value="{{ $rombel->id }}">
                                            {{ $className }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <button
                                type="button"
                                onclick="addScheduleForSelectedClass({{ $mapel->id }})"
                                class="rounded-lg bg-black px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800"
                            >
                                Tambahkan Jadwal
                            </button>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ISI JADWAL --}}
                    {{-- ================================================= --}}

                    <div class="p-5">

                        <div
                            class="edit-class-container space-y-5"
                            data-mapel-id="{{ $mapel->id }}"
                        >

                            @forelse ($groupedSchedules as $rombelId => $schedules)

                                @php

                                    $rombel =
                                        $rombels->firstWhere(
                                            'id',
                                            $rombelId
                                        );

                                    $className =
                                        $rombel
                                            ? trim(
                                                $rombel->jenjang
                                                . ' '
                                                . ($rombel->schoolMajor?->major?->name ?? '')
                                                . ' '
                                                . $rombel->name
                                            )
                                            : 'Kelas';

                                @endphp


                                <div
                                    class="edit-class-group rounded-lg border border-gray-200"
                                    data-rombel-id="{{ $rombelId }}"
                                >

                                    {{-- HEADER KELAS --}}
                                    <div class="border-b bg-gray-50 px-4 py-3">

                                        <span class="font-semibold text-gray-800">
                                            {{ $className }}
                                        </span>

                                    </div>


                                    {{-- HEADER KOLOM --}}
                                    <div class="grid grid-cols-[1fr_1fr_1.5fr_1.5fr_50px] gap-3 border-b px-4 py-3 text-sm font-semibold text-gray-600">

                                        <div>
                                            Hari
                                        </div>

                                        <div>
                                            Jam Mulai
                                        </div>

                                        <div>
                                            Jam Selesai
                                        </div>

                                        <div>
                                            -
                                        </div>

                                        <div>
                                        </div>

                                    </div>


                                    {{-- ROW JADWAL --}}
                                    <div
                                        class="edit-class-schedule-list"
                                        data-mapel-id="{{ $mapel->id }}"
                                        data-rombel-id="{{ $rombelId }}"
                                    >

                                        @foreach ($schedules as $scheduleIndex => $schedule)

                                            <div
                                                class="edit-schedule-row grid grid-cols-[1fr_1fr_1.5fr_1.5fr_50px] items-center gap-3 border-b px-4 py-3 last:border-b-0"
                                                data-existing="true"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="schedules[{{ $mapel->id }}_{{ $rombelId }}_{{ $scheduleIndex }}][school_mapel_id]"
                                                    value="{{ $mapel->id }}"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="schedules[{{ $mapel->id }}_{{ $rombelId }}_{{ $scheduleIndex }}][rombel_id]"
                                                    value="{{ $rombelId }}"
                                                >


                                                {{-- HARI --}}
                                                <div>

                                                    <select
                                                        name="schedules[{{ $mapel->id }}_{{ $rombelId }}_{{ $scheduleIndex }}][hari]"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                        required
                                                    >

                                                        @foreach ($days as $day)

                                                            <option
                                                                value="{{ $day }}"
                                                                @selected(
                                                                    strtolower($schedule->hari) === $day
                                                                )
                                                            >
                                                                {{ ucfirst($day) }}
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- JAM MULAI --}}
                                                <div>

                                                    <select
                                                        name="schedules[{{ $mapel->id }}_{{ $rombelId }}_{{ $scheduleIndex }}][lesson_period_start_id]"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                        required
                                                    >

                                                        @foreach ($lessonPeriods as $period)

                                                            <option
                                                                value="{{ $period->id }}"
                                                                @selected(
                                                                    $period->id == $schedule->lesson_period_start_id
                                                                )
                                                            >
                                                                {{ $period->jam_ke }}
                                                                ({{ substr($period->jam_mulai, 0, 5) }}-{{ substr($period->jam_selesai, 0, 5) }})
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- JAM SELESAI --}}
                                                <div>

                                                    <select
                                                        name="schedules[{{ $mapel->id }}_{{ $rombelId }}_{{ $scheduleIndex }}][lesson_period_end_id]"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                                        required
                                                    >

                                                        @foreach ($lessonPeriods as $period)

                                                            <option
                                                                value="{{ $period->id }}"
                                                                @selected(
                                                                    $period->id == $schedule->lesson_period_end_id
                                                                )
                                                            >
                                                                {{ $period->jam_ke }}
                                                                ({{ substr($period->jam_mulai, 0, 5) }}-{{ substr($period->jam_selesai, 0, 5) }})
                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- PEMISAH --}}
                                                <div class="text-center text-gray-400">
                                                    -
                                                </div>


                                                {{-- DELETE --}}
                                                <div class="text-center">

                                                    <button
                                                        type="button"
                                                        onclick="removeEditScheduleRow(this)"
                                                        class="rounded-lg p-2 text-red-500 hover:bg-red-50"
                                                        title="Hapus jadwal"
                                                    >
                                                        🗑
                                                    </button>

                                                </div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            @empty

                                <div class="edit-empty-schedule rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">

                                    Belum ada jadwal mengajar.

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- ===================================================== --}}
        {{-- BUTTON SAVE --}}
        {{-- ===================================================== --}}

        <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow-sm">

            <a
                href="{{ route('teachers.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Batal
            </a>


            <button
                type="submit"
                class="rounded-lg bg-black px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- DATA UNTUK JAVASCRIPT --}}
{{-- ============================================================= --}}

@php

    /*
    |--------------------------------------------------------------------------
    | DATA ROMBEL
    |--------------------------------------------------------------------------
    */

    $rombelsData = $rombels
        ->map(function ($rombel) {

            return [
                'id' => (int) $rombel->id,

                'name' => trim(
                    $rombel->jenjang
                    . ' '
                    . ($rombel->schoolMajor?->major?->name ?? '')
                    . ' '
                    . $rombel->name
                ),
            ];

        })
        ->values()
        ->all();


    /*
    |--------------------------------------------------------------------------
    | DATA LESSON PERIOD
    |--------------------------------------------------------------------------
    */

    $lessonPeriodsData = $lessonPeriods
        ->map(function ($period) {

            return [
                'id' => (int) $period->id,

                'jam_ke' => (int) $period->jam_ke,

                'jam_mulai' => substr(
                    $period->jam_mulai,
                    0,
                    5
                ),

                'jam_selesai' => substr(
                    $period->jam_selesai,
                    0,
                    5
                ),
            ];

        })
        ->values()
        ->all();


    /*
    |--------------------------------------------------------------------------
    | DATA HARI
    |--------------------------------------------------------------------------
    */

    $daysData = collect($days)
        ->values()
        ->all();

@endphp


<script>

    const rombels = @json($rombelsData);

    const periods = @json($lessonPeriodsData);

    const days = @json($daysData);


    let editSubjectIndex =
        {{ count($editSubjects) }};

    let editScheduleIndex =
        1000;


    /*
    |--------------------------------------------------------------------------
    | Tambah Mata Pelajaran
    |--------------------------------------------------------------------------
    */

    function addEditSubject()
    {
        const container =
            document.getElementById(
                'edit-subject-container'
            );

        const index =
            editSubjectIndex++;

        const card =
            document.createElement('div');

        card.className =
            'edit-subject-card mb-6 rounded-xl border border-gray-200 p-5';


        let mapelOptions = `
            <option value="">
                Pilih Mata Pelajaran
            </option>
        `;


        @foreach ($schoolMapels as $schoolMapel)

            mapelOptions += `
                <option value="{{ $schoolMapel->id }}">
                    {{ addslashes($schoolMapel->masterMapel?->name ?? 'Tanpa Nama') }}
                </option>
            `;

        @endforeach


        let rombelHtml = '';


        rombels.forEach(function (rombel) {

            rombelHtml += `
                <label
                    class="edit-rombel-item flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50"
                    data-search="${rombel.name.toLowerCase()}"
                >

                    <input
                        type="checkbox"
                        name="subjects[${index}][rombel_ids][]"
                        value="${rombel.id}"
                        class="h-4 w-4 rounded border-gray-300 text-blue-600"
                    >

                    <span class="text-sm font-medium text-gray-700">
                        ${rombel.name}
                    </span>

                </label>
            `;

        });


        card.innerHTML = `

            <div class="mb-5 flex items-center justify-between">

                <h3 class="font-semibold text-gray-800">
                    Mata Pelajaran
                </h3>

                <button
                    type="button"
                    onclick="removeEditSubject(this)"
                    class="text-sm font-medium text-red-500 hover:text-red-700"
                >
                    Hapus
                </button>

            </div>


            <div class="mb-5">

                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Mata Pelajaran
                    <span class="text-red-500">*</span>
                </label>

                <select
                    name="subjects[${index}][school_mapel_id]"
                    class="edit-mapel-select w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    required
                >

                    ${mapelOptions}

                </select>

            </div>


            <div class="mb-4">

                <input
                    type="text"
                    class="edit-rombel-search w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm"
                    placeholder="Cari kelas..."
                    onkeyup="searchEditRombel(this)"
                >

            </div>


            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">

                ${rombelHtml}

            </div>

        `;


        container.appendChild(card);
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus Mata Pelajaran
    |--------------------------------------------------------------------------
    */

    function removeEditSubject(button)
    {
        const card =
            button.closest(
                '.edit-subject-card'
            );

        if (card) {
            card.remove();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Cari Rombel
    |--------------------------------------------------------------------------
    */

    function searchEditRombel(input)
    {
        const card =
            input.closest(
                '.edit-subject-card'
            );

        if (!card) {
            return;
        }


        const keyword =
            input.value
                .toLowerCase()
                .trim();


        card
            .querySelectorAll(
                '.edit-rombel-item'
            )
            .forEach(function (item) {

                const text =
                    item.dataset.search || '';


                item.style.display =
                    text.includes(keyword)
                        ? ''
                        : 'none';

            });
    }


    /*
    |--------------------------------------------------------------------------
    | Tambah Jadwal
    |--------------------------------------------------------------------------
    */

    function addEditScheduleRow(mapelId)
    {
        const card =
            document.querySelector(
                `[data-edit-schedule-card][data-mapel-id="${mapelId}"]`
            );

        if (!card) {
            return;
        }


        const body =
            card.querySelector(
                '.edit-schedule-body'
            );

        if (!body) {
            return;
        }


        const index =
            editScheduleIndex++;


        let rombelOptions = '';


        rombels.forEach(function (rombel) {

            rombelOptions += `
                <option value="${rombel.id}">
                    ${rombel.name}
                </option>
            `;

        });


        let periodOptions = '';


        periods.forEach(function (period) {

            periodOptions += `
                <option value="${period.id}">
                    Jam ${period.jam_ke}
                    (${period.jam_mulai} - ${period.jam_selesai})
                </option>
            `;

        });


        const row =
            document.createElement('tr');

        row.className =
            'edit-schedule-row border-b';


        row.innerHTML = `

            <input
                type="hidden"
                name="schedules[${index}][school_mapel_id]"
                value="${mapelId}"
            >


            <td class="px-3 py-3">

                <select
                    name="schedules[${index}][rombel_id]"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                    required
                >

                    ${rombelOptions}

                </select>

            </td>


            <td class="px-3 py-3">

                <select
                    name="schedules[${index}][hari]"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                    required
                >

                    ${days.map(function(day) {

                        return `
                            <option value="${day}">
                                ${day.charAt(0).toUpperCase() + day.slice(1)}
                            </option>
                        `;

                    }).join('')}

                </select>

            </td>


            <td class="px-3 py-3">

                <select
                    name="schedules[${index}][lesson_period_start_id]"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                    required
                >

                    ${periodOptions}

                </select>

            </td>


            <td class="px-3 py-3">

                <select
                    name="schedules[${index}][lesson_period_end_id]"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                    required
                >

                    ${periodOptions}

                </select>

            </td>


            <td class="px-3 py-3 text-center">

                <button
                    type="button"
                    onclick="removeEditScheduleRow(this)"
                    class="rounded-lg p-2 text-red-500 hover:bg-red-50"
                >
                    🗑
                </button>

            </td>

        `;


        body.appendChild(row);
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus Jadwal
    |--------------------------------------------------------------------------
    */

    function removeEditScheduleRow(button)
    {
        const row =
            button.closest(
                '.edit-schedule-row'
            );

        if (!row) {
            return;
        }


        row.remove();
    }


    /*
    |--------------------------------------------------------------------------
    | Validasi Submit
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('edit-teacher-form')
        .addEventListener(
            'submit',
            function (event) {

                const subjects =
                    document.querySelectorAll(
                        '.edit-subject-card'
                    );


                if (subjects.length === 0) {

                    event.preventDefault();

                    alert(
                        'Minimal satu mata pelajaran harus dipilih.'
                    );

                    return;
                }


                const selectedMapels = [];


                for (const subject of subjects) {

                    const select =
                        subject.querySelector(
                            '.edit-mapel-select'
                        );


                    const mapel =
                        select
                            ? select.value
                            : '';


                    const classes =
                        subject.querySelectorAll(
                            'input[type="checkbox"]:checked'
                        );


                    if (!mapel) {

                        event.preventDefault();

                        alert(
                            'Silakan pilih mata pelajaran.'
                        );

                        return;
                    }


                    if (classes.length === 0) {

                        event.preventDefault();

                        alert(
                            'Setiap mata pelajaran harus memiliki minimal satu kelas.'
                        );

                        return;
                    }


                    if (
                        selectedMapels.includes(mapel)
                    ) {

                        event.preventDefault();

                        alert(
                            'Mata pelajaran yang sama tidak boleh dipilih dua kali.'
                        );

                        return;
                    }


                    selectedMapels.push(mapel);

                }

            }
        );

</script>

@endsection