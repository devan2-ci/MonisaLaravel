@extends('layout.app')

@section('content')

<div class="min-h-screen bg-slate-50 px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-6xl">

        {{-- =========================================================
            DATA YANG DISIAPKAN UNTUK JAVASCRIPT
        ========================================================== --}}
        @php

            /*
            |--------------------------------------------------------------------------
            | Data Rombel
            |--------------------------------------------------------------------------
            */

            $rombelsJson = [];

            foreach ($rombels as $rombel) {
                $rombelsJson[] = [
                    'id' => $rombel->id,
                    'jenjang' => $rombel->jenjang,
                    'name' => $rombel->name,
                    'major_id' => $rombel->schoolMajor->major->id ?? null,
                    'major_name' => $rombel->schoolMajor->major->name ?? '-',
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Assignment Lama Dari Session
            |--------------------------------------------------------------------------
            |
            | Format yang diharapkan:
            |
            | [
            |     [
            |         'school_mapel_id' => 1,
            |         'rombel_ids' => [1, 2, 3]
            |     ]
            | ]
            |
            */

            $assignmentData = [];

            if (!empty($assignments)) {

                foreach ($assignments as $assignment) {

                    if (is_object($assignment)) {
                        $assignment = (array) $assignment;
                    }

                    $mapelId = $assignment['school_mapel_id'] ?? null;

                    $rombelIds = $assignment['rombel_ids'] ?? [];

                    if ($mapelId) {

                        $assignmentData[$mapelId] = array_values(
                            array_map(
                                'intval',
                                is_array($rombelIds)
                                    ? $rombelIds
                                    : []
                            )
                        );
                    }
                }
            }

        @endphp


        {{-- =========================================================
            HEADER LANGKAH 2
        ========================================================== --}}

        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex items-start gap-4">

                {{-- Icon step --}}
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-4 border-slate-300 bg-white">
                    <div class="h-3 w-3 rounded-full bg-slate-700"></div>
                </div>

                <div class="min-w-0 flex-1">

                    <h1 class="text-2xl font-semibold text-slate-900">
                        Langkah 2
                    </h1>

                    <div class="my-4 border-t border-slate-200"></div>

                    <p class="text-sm text-slate-600 sm:text-base">
                        Tentukan kelas yang akan diajar.
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
            ERROR VALIDASI
        ========================================================== --}}

        @if ($errors->any())

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="mb-2 font-semibold text-red-700">
                    Terdapat kesalahan:
                </div>

                <ul class="list-inside list-disc space-y-1 text-sm text-red-600">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
            FORM
        ========================================================== --}}

        <form
            action="{{ route('teachers.create.step2.store') }}"
            method="POST"
            id="step2Form"
        >

            @csrf


            {{-- =====================================================
                DATA GURU
            ====================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                {{-- Header --}}
                <button
                    type="button"
                    onclick="toggleGuru()"
                    class="flex w-full items-center justify-between px-6 py-5 text-left"
                >

                    <div class="flex items-center gap-3">

                        <svg
                            class="h-5 w-5 text-slate-700"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                            />

                            <circle
                                cx="9"
                                cy="7"
                                r="4"
                                stroke-width="1.8"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-width="1.8"
                                d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                            />
                        </svg>

                        <span class="text-lg font-semibold text-slate-900">
                            Data Guru
                        </span>

                    </div>


                    <svg
                        id="guruChevron"
                        class="h-5 w-5 text-slate-600 transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>

                </button>


                {{-- Content --}}
                <div
                    id="guruContent"
                    class="border-t border-slate-200 px-6 py-6"
                >

                    <div class="grid grid-cols-1 gap-x-10 gap-y-6 md:grid-cols-2">

                        {{-- Nama --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                Nama Guru
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['name'] ?? '-' }}
                            </p>
                        </div>


                        {{-- NIP --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                NIP
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['nip'] ?? '-' }}
                            </p>
                        </div>


                        {{-- NUPTK --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                NUPTK
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['nuptk'] ?? '-' }}
                            </p>
                        </div>


                        {{-- Gender --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                Jenis Kelamin
                            </p>

                            <p class="text-sm text-slate-600">

                                @if (($teacherData['gender'] ?? '') === 'l')
                                    Laki-laki
                                @elseif (($teacherData['gender'] ?? '') === 'p')
                                    Perempuan
                                @else
                                    -
                                @endif

                            </p>
                        </div>


                        {{-- Tanggal lahir --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                Tanggal Lahir
                            </p>

                            <p class="text-sm text-slate-600">

                                @if (!empty($teacherData['tanggal_lahir']))

                                    {{ \Carbon\Carbon::parse($teacherData['tanggal_lahir'])->format('d-m-Y') }}

                                @else
                                    -
                                @endif

                            </p>
                        </div>


                        {{-- Alamat --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                Alamat
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['alamat'] ?? '-' }}
                            </p>
                        </div>


                        {{-- Handphone --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                No. Handphone
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['no_hp'] ?? '-' }}
                            </p>
                        </div>


                        {{-- Email --}}
                        <div>
                            <p class="mb-1 text-sm font-semibold text-slate-900">
                                E-Mail
                            </p>

                            <p class="text-sm text-slate-600">
                                {{ $teacherData['email'] ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                MATA PELAJARAN
            ====================================================== --}}

            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center gap-3 px-6 py-5">

                    <svg
                        class="h-5 w-5 text-slate-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M5 4h10a2 2 0 012 2v14H7a2 2 0 01-2-2V4z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M7 20h12V6a2 2 0 00-2-2"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M9 8h5M9 12h5"
                        />
                    </svg>

                    <h2 class="text-lg font-semibold text-slate-900">
                        Mata Pelajaran
                    </h2>

                </div>

                <div class="border-t border-slate-200 px-6 py-6">

                    @forelse ($schoolMapels as $index => $schoolMapel)

                        <div class="{{ $index > 0 ? 'mt-4 border-t border-slate-200 pt-4' : '' }}">

                            <p class="text-sm font-medium text-slate-500">
                                Mata pelajaran {{ $index + 1 }}
                            </p>

                            <p class="mt-1 text-base font-semibold text-slate-900">
                                {{ $schoolMapel->masterMapel->name ?? '-' }}
                            </p>

                        </div>

                    @empty

                        <p class="text-sm text-slate-500">
                            Tidak ada mata pelajaran yang dipilih.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                SECTION KELAS PER MAPEL
            ====================================================== --}}

            @forelse ($schoolMapels as $mapelIndex => $schoolMapel)

                @php

                    $mapelId = $schoolMapel->id;

                    $mapelAssignments =
                        $assignmentData[$mapelId] ?? [];

                @endphp


                <div
                    class="mapel-section mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                    data-mapel-id="{{ $mapelId }}"
                >

                    {{-- =================================================
                        HEADER MAPEL
                    ================================================== --}}

                    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <svg
                                class="h-5 w-5 text-slate-700"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M5 6h14M5 12h14M5 18h14"
                                />
                            </svg>

                            <h2 class="text-lg font-semibold text-slate-900">
                                Kelas -
                                {{ $schoolMapel->masterMapel->name ?? '-' }}
                            </h2>

                        </div>


                        {{-- Search --}}
                        <div class="relative w-full sm:w-52">

                            <input
                                type="text"
                                class="class-search w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-4 pr-10 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                placeholder="Cari"
                                data-mapel="{{ $mapelId }}"
                                oninput="searchClass(this)"
                            >

                            <svg
                                class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>

                        </div>

                    </div>


                    {{-- =================================================
                        ISI KELAS
                    ================================================== --}}

                    <div class="px-6 py-6">

                        {{-- Header tabel --}}
                        <div class="mb-3 hidden grid-cols-[1fr_1fr_1fr_48px] gap-4 md:grid">

                            <div class="text-sm font-semibold text-slate-700">
                                Jenjang
                            </div>

                            <div class="text-sm font-semibold text-slate-700">
                                Jurusan
                            </div>

                            <div class="text-sm font-semibold text-slate-700">
                                Abjad
                            </div>

                            <div></div>

                        </div>


                        {{-- Container rows --}}
                        <div
                            class="class-rows space-y-3"
                            data-mapel="{{ $mapelId }}"
                        >

                            @if (count($mapelAssignments) > 0)

                                @foreach ($mapelAssignments as $rombelId)

                                    @php
                                        $selectedRombel =
                                            $rombels->firstWhere('id', $rombelId);
                                    @endphp

                                    <div
                                        class="class-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_1fr_48px]"
                                        data-rombel-name="{{ strtolower($selectedRombel->name ?? '') }}"
                                    >

                                        {{-- Hidden mapel --}}
                                        <input
                                            type="hidden"
                                            name="assignments[{{ $mapelIndex }}][school_mapel_id]"
                                            value="{{ $mapelId }}"
                                        >


                                        {{-- Jenjang --}}
                                        <div>

                                            <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                                Jenjang
                                            </label>

                                            <select
                                                name="assignments[{{ $mapelIndex }}][jenjang][]"
                                                class="jenjang-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                                onchange="changeJenjang(this)"
                                            >

                                                <option value="">
                                                    Pilih
                                                </option>

                                                @php
                                                    $jenjangs = $rombels
                                                        ->pluck('jenjang')
                                                        ->unique()
                                                        ->sort()
                                                        ->values();
                                                @endphp

                                                @foreach ($jenjangs as $jenjang)

                                                    <option
                                                        value="{{ $jenjang }}"
                                                        @selected(($selectedRombel->jenjang ?? '') == $jenjang)
                                                    >
                                                        {{ $jenjang }}
                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>


                                        {{-- Jurusan --}}
                                        <div>

                                            <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                                Jurusan
                                            </label>

                                            <select
                                                name="assignments[{{ $mapelIndex }}][major_id][]"
                                                class="jurusan-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                                onchange="changeJurusan(this)"
                                            >

                                                <option value="">
                                                    Pilih
                                                </option>

                                                @if ($selectedRombel && $selectedRombel->schoolMajor)

                                                    <option
                                                        value="{{ $selectedRombel->schoolMajor->major->id ?? '' }}"
                                                        selected
                                                    >
                                                        {{ $selectedRombel->schoolMajor->major->name ?? '-' }}
                                                    </option>

                                                @endif

                                            </select>

                                        </div>


                                        {{-- Abjad / Rombel --}}
                                        <div>

                                            <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                                Abjad
                                            </label>

                                            <select
                                                name="assignments[{{ $mapelIndex }}][rombel_ids][]"
                                                class="rombel-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                                onchange="changeRombel(this)"
                                            >

                                                <option value="">
                                                    Pilih
                                                </option>

                                                @if ($selectedRombel)

                                                    <option
                                                        value="{{ $selectedRombel->id }}"
                                                        selected
                                                    >
                                                        {{ $selectedRombel->name }}
                                                    </option>

                                                @endif

                                            </select>

                                        </div>


                                        {{-- Hapus --}}
                                        <div class="flex items-center justify-end md:justify-center">

                                            <button
                                                type="button"
                                                onclick="removeClassRow(this)"
                                                class="inline-flex h-12 w-12 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                                                title="Hapus kelas"
                                            >

                                                <svg
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a1 1 0 01-1 1H8a1 1 0 01-1-1V7m3 4v6m4-6v6"
                                                    />
                                                </svg>

                                            </button>

                                        </div>

                                    </div>

                                @endforeach

                            @else

                                {{-- Row default --}}
                                <div
                                    class="class-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_1fr_48px]"
                                    data-rombel-name=""
                                >

                                    {{-- Mapel --}}
                                    <input
                                        type="hidden"
                                        name="assignments[{{ $mapelIndex }}][school_mapel_id]"
                                        value="{{ $mapelId }}"
                                    >


                                    {{-- Jenjang --}}
                                    <div>

                                        <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                            Jenjang
                                        </label>

                                        <select
                                            name="assignments[{{ $mapelIndex }}][jenjang][]"
                                            class="jenjang-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                            onchange="changeJenjang(this)"
                                        >

                                            <option value="">
                                                Pilih
                                            </option>

                                            @php
                                                $jenjangs = $rombels
                                                    ->pluck('jenjang')
                                                    ->unique()
                                                    ->sort()
                                                    ->values();
                                            @endphp

                                            @foreach ($jenjangs as $jenjang)

                                                <option value="{{ $jenjang }}">
                                                    {{ $jenjang }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- Jurusan --}}
                                    <div>

                                        <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                            Jurusan
                                        </label>

                                        <select
                                            name="assignments[{{ $mapelIndex }}][major_id][]"
                                            class="jurusan-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                            onchange="changeJurusan(this)"
                                        >

                                            <option value="">
                                                Pilih
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Abjad --}}
                                    <div>

                                        <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                                            Abjad
                                        </label>

                                        <select
                                            name="assignments[{{ $mapelIndex }}][rombel_ids][]"
                                            class="rombel-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                                            onchange="changeRombel(this)"
                                        >

                                            <option value="">
                                                Pilih
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Hapus --}}
                                    <div class="flex items-center justify-end md:justify-center">

                                        <button
                                            type="button"
                                            onclick="removeClassRow(this)"
                                            class="inline-flex h-12 w-12 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                                            title="Hapus kelas"
                                        >

                                            <svg
                                                class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a1 1 0 01-1 1H8a1 1 0 01-1-1V7m3 4v6m4-6v6"
                                                />
                                            </svg>

                                        </button>

                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                            BUTTON TAMBAH
                        ================================================== --}}

                        <button
                            type="button"
                            onclick="addClassRow({{ $mapelId }}, {{ $mapelIndex }})"
                            class="mt-5 inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 transition hover:bg-slate-50"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 5v14M5 12h14"
                                />
                            </svg>

                            Tambah

                        </button>

                    </div>

                </div>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">

                    <p class="text-sm text-slate-500">
                        Belum ada mata pelajaran yang dipilih.
                    </p>

                </div>

            @endforelse


            {{-- =========================================================
                BUTTON BAWAH
            ========================================================== --}}

            <div class="flex items-center justify-between gap-4 pb-8">

                <a
                    href="{{ route('teachers.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Kembali

                </a>


                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
                >

                    Selanjutnya

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | DATA ROMBEL DARI LARAVEL
    |--------------------------------------------------------------------------
    */

    const allRombels = @json($rombelsJson);


    /*
    |--------------------------------------------------------------------------
    | TOGGLE DATA GURU
    |--------------------------------------------------------------------------
    */

    function toggleGuru() {

        const content =
            document.getElementById('guruContent');

        const chevron =
            document.getElementById('guruChevron');


        if (content.classList.contains('hidden')) {

            content.classList.remove('hidden');

            chevron.classList.remove('rotate-180');

        } else {

            content.classList.add('hidden');

            chevron.classList.add('rotate-180');

        }
    }


    /*
    |--------------------------------------------------------------------------
    | GET JENJANG
    |--------------------------------------------------------------------------
    */

    function getJenjangs() {

        const jenjangs = [];

        allRombels.forEach(function (rombel) {

            if (!jenjangs.includes(rombel.jenjang)) {

                jenjangs.push(rombel.jenjang);

            }

        });

        return jenjangs.sort();
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE JENJANG
    |--------------------------------------------------------------------------
    */

    function changeJenjang(select) {

        const row =
            select.closest('.class-row');

        const jenjang =
            select.value;

        const jurusanSelect =
            row.querySelector('.jurusan-select');

        const rombelSelect =
            row.querySelector('.rombel-select');


        /*
        |--------------------------------------------------------------------------
        | Reset jurusan
        |--------------------------------------------------------------------------
        */

        jurusanSelect.innerHTML =
            '<option value="">Pilih</option>';

        /*
        |--------------------------------------------------------------------------
        | Reset rombel
        |--------------------------------------------------------------------------
        */

        rombelSelect.innerHTML =
            '<option value="">Pilih</option>';


        if (!jenjang) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil jurusan berdasarkan jenjang
        |--------------------------------------------------------------------------
        */

        const majors = [];


        allRombels.forEach(function (rombel) {

            if (rombel.jenjang == jenjang) {

                if (!rombel.major_id) {
                    return;
                }


                const exists =
                    majors.some(function (major) {

                        return major.id == rombel.major_id;

                    });


                if (!exists) {

                    majors.push({
                        id: rombel.major_id,
                        name: rombel.major_name
                    });

                }

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Masukkan jurusan
        |--------------------------------------------------------------------------
        */

        majors.forEach(function (major) {

            const option =
                document.createElement('option');

            option.value =
                major.id;

            option.textContent =
                major.name;

            jurusanSelect.appendChild(option);

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE JURUSAN
    |--------------------------------------------------------------------------
    */

    function changeJurusan(select) {

        const row =
            select.closest('.class-row');

        const jenjang =
            row.querySelector('.jenjang-select').value;

        const majorId =
            select.value;

        const rombelSelect =
            row.querySelector('.rombel-select');


        /*
        |--------------------------------------------------------------------------
        | Reset rombel
        |--------------------------------------------------------------------------
        */

        rombelSelect.innerHTML =
            '<option value="">Pilih</option>';


        if (!jenjang || !majorId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil rombel
        |--------------------------------------------------------------------------
        */

        allRombels.forEach(function (rombel) {

            if (
                rombel.jenjang == jenjang &&
                rombel.major_id == majorId
            ) {

                const option =
                    document.createElement('option');

                option.value =
                    rombel.id;

                option.textContent =
                    rombel.name;

                rombelSelect.appendChild(option);

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE ROMBEL
    |--------------------------------------------------------------------------
    */

    function changeRombel(select) {

        /*
        | Tidak perlu hidden input.
        | Value langsung dikirim melalui:
        |
        | assignments[index][rombel_ids][]
        |
        */

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH BARIS KELAS
    |--------------------------------------------------------------------------
    */

    function addClassRow(mapelId, mapelIndex) {

        const container =
            document.querySelector(
                `.class-rows[data-mapel="${mapelId}"]`
            );


        if (!container) {
            return;
        }


        const row =
            document.createElement('div');


        row.className =
            'class-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_1fr_48px]';


        row.dataset.rombelName = '';


        /*
        |--------------------------------------------------------------------------
        | HTML BARIS BARU
        |--------------------------------------------------------------------------
        */

        row.innerHTML = `

            <input
                type="hidden"
                name="assignments[${mapelIndex}][school_mapel_id]"
                value="${mapelId}"
            >

            <!-- JENJANG -->

            <div>

                <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                    Jenjang
                </label>

                <select
                    name="assignments[${mapelIndex}][jenjang][]"
                    class="jenjang-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                    onchange="changeJenjang(this)"
                >

                    <option value="">
                        Pilih
                    </option>

                    ${getJenjangs()
                        .map(function(jenjang) {

                            return `
                                <option value="${jenjang}">
                                    ${jenjang}
                                </option>
                            `;

                        })
                        .join('')
                    }

                </select>

            </div>


            <!-- JURUSAN -->

            <div>

                <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                    Jurusan
                </label>

                <select
                    name="assignments[${mapelIndex}][major_id][]"
                    class="jurusan-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                    onchange="changeJurusan(this)"
                >

                    <option value="">
                        Pilih
                    </option>

                </select>

            </div>


            <!-- ROMBEL -->

            <div>

                <label class="mb-1 block text-xs font-medium text-slate-500 md:hidden">
                    Abjad
                </label>

                <select
                    name="assignments[${mapelIndex}][rombel_ids][]"
                    class="rombel-select w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                    onchange="changeRombel(this)"
                >

                    <option value="">
                        Pilih
                    </option>

                </select>

            </div>


            <!-- HAPUS -->

            <div class="flex items-center justify-end md:justify-center">

                <button
                    type="button"
                    onclick="removeClassRow(this)"
                    class="inline-flex h-12 w-12 items-center justify-center rounded-lg border border-slate-300 text-slate-600 transition hover:border-red-300 hover:bg-red-50 hover:text-red-600"
                    title="Hapus kelas"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m2 0v12a1 1 0 01-1 1H8a1 1 0 01-1-1V7m3 4v6m4-6v6"
                        />

                    </svg>

                </button>

            </div>

        `;


        container.appendChild(row);

    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS BARIS KELAS
    |--------------------------------------------------------------------------
    */

    function removeClassRow(button) {

        const row =
            button.closest('.class-row');

        const container =
            row.parentElement;


        /*
        |--------------------------------------------------------------------------
        | Jangan sampai tidak ada baris sama sekali.
        |--------------------------------------------------------------------------
        */

        const rows =
            container.querySelectorAll('.class-row');


        if (rows.length <= 1) {

            /*
            | Kalau hanya tersisa satu row,
            | kosongkan pilihannya saja.
            */

            const jenjang =
                row.querySelector('.jenjang-select');

            const jurusan =
                row.querySelector('.jurusan-select');

            const rombel =
                row.querySelector('.rombel-select');


            if (jenjang) {
                jenjang.value = '';
            }

            if (jurusan) {
                jurusan.innerHTML =
                    '<option value="">Pilih</option>';
            }

            if (rombel) {
                rombel.innerHTML =
                    '<option value="">Pilih</option>';
            }

            return;
        }


        row.remove();

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH KELAS
    |--------------------------------------------------------------------------
    */

    function searchClass(input) {

        const mapelId =
            input.dataset.mapel;

        const keyword =
            input.value.toLowerCase().trim();


        const container =
            document.querySelector(
                `.class-rows[data-mapel="${mapelId}"]`
            );


        if (!container) {
            return;
        }


        const rows =
            container.querySelectorAll('.class-row');


        rows.forEach(function (row) {

            const jenjang =
                row.querySelector('.jenjang-select')?.value
                ?? '';

            const jurusan =
                row.querySelector('.jurusan-select')?.selectedOptions[0]?.text
                ?? '';

            const rombel =
                row.querySelector('.rombel-select')?.selectedOptions[0]?.text
                ?? '';


            const text = (
                jenjang +
                ' ' +
                jurusan +
                ' ' +
                rombel
            ).toLowerCase();


            if (!keyword || text.includes(keyword)) {

                row.classList.remove('hidden');

            } else {

                row.classList.add('hidden');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('step2Form')
        .addEventListener('submit', function (event) {

            let valid = true;


            /*
            |--------------------------------------------------------------------------
            | Periksa setiap mapel
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.mapel-section')
                .forEach(function (section) {

                    const rows =
                        section.querySelectorAll(
                            '.class-row'
                        );


                    let hasSelectedRombel =
                        false;


                    rows.forEach(function (row) {

                        const rombel =
                            row.querySelector(
                                '.rombel-select'
                            );


                        if (
                            rombel &&
                            rombel.value
                        ) {

                            hasSelectedRombel =
                                true;

                        }

                    });


                    if (!hasSelectedRombel) {

                        valid = false;

                    }

                });


            /*
            |--------------------------------------------------------------------------
            | Jika tidak valid
            |--------------------------------------------------------------------------
            */

            if (!valid) {

                event.preventDefault();

                alert(
                    'Setiap mata pelajaran harus memiliki minimal satu kelas.'
                );

            }

        });


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE DATA LAMA
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            /*
            | Jika ada data lama dari session,
            | jurusan dan rombel sudah dibuat oleh Blade.
            |
            | Tidak perlu menjalankan perubahan otomatis.
            */

        }
    );

</script>

@endsection