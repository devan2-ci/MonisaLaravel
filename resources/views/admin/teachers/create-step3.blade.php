@extends('layout.app')

@section('content')

<div class="w-full px-6 py-8 lg:px-10">

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-7 rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <div class="text-xl text-red-600">
                    ⚠
                </div>

                <div>
                    <h3 class="font-semibold text-red-700">
                        Data belum dapat disimpan
                    </h3>

                    <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- SESSION ERROR --}}
    @if (session('error'))
        <div class="mb-7 rounded-2xl border border-red-200 bg-red-50 p-5">
            <p class="text-sm font-medium text-red-700">
                {{ session('error') }}
            </p>
        </div>
    @endif


    {{-- STEP 3 --}}
    <div class="mb-7 rounded-2xl border border-gray-200 bg-white p-7">
        <div class="flex items-center gap-3">
            <div
                class="flex h-7 w-7 items-center justify-center rounded-full border-[6px] border-gray-300 border-r-gray-900"
            >
            </div>

            <h1 class="text-2xl font-semibold text-gray-900">
                Langkah 3
            </h1>
        </div>

        <div class="mt-5 border-t border-gray-300 pt-5">
            <p class="text-base text-gray-800">
                Atur jadwal mengajar untuk guru di sekolah.
            </p>
        </div>
    </div>


    {{-- FORM --}}
    <form action="{{ route('teachers.create.step3.store') }}" method="POST" id="step3-form">

        @csrf

        {{-- DATA GURU --}}
        <div class="mb-7 overflow-visible rounded-2xl border border-gray-200 bg-white">
            <button
                type="button"
                class="flex w-full items-center justify-between p-7 text-left"
                onclick="toggleAccordion(
                    'guru-content',
                    'guru-icon'
                )"
            >
                <div class="flex items-center gap-3">
                    <span class="text-xl text-gray-900">
                        ♙
                    </span>

                    <span class="text-lg font-semibold text-gray-900">
                        Data Guru
                    </span>
                </div>

                <span id="guru-icon" class="text-xl text-gray-900">
                    ⌄
                </span>
            </button>


            <div
                id="guru-content"
                class="hidden border-t border-gray-200 px-7 pb-7 pt-6"
            >

                <div class="grid grid-cols-1 gap-x-10 gap-y-5 md:grid-cols-2">

                    {{-- NAMA --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Nama Guru
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.name') ?? '-' }}
                        </p>

                    </div>


                    {{-- NIP --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            NIP
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.nip') ?? '-' }}
                        </p>

                    </div>


                    {{-- NUPTK --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            NUPTK
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.nuptk') ?? '-' }}
                        </p>

                    </div>


                    {{-- GENDER --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Jenis Kelamin
                        </p>

                        <p class="mt-1 font-medium text-gray-900">

                            @if (
                                session(
                                    'teacher_create.step1.gender'
                                ) === 'l'
                            )

                                Laki-laki

                            @elseif (
                                session(
                                    'teacher_create.step1.gender'
                                ) === 'p'
                            )

                                Perempuan

                            @else

                                -

                            @endif

                        </p>

                    </div>


                    {{-- TANGGAL LAHIR --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Tanggal Lahir
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.tanggal_lahir') ?? '-' }}
                        </p>

                    </div>


                    {{-- EMAIL --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Email
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.email') ?? '-' }}
                        </p>

                    </div>


                    {{-- NO HP --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            No. Handphone
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.no_hp') ?? '-' }}
                        </p>

                    </div>


                    {{-- ALAMAT --}}
                    <div>

                        <p class="text-sm text-gray-500">
                            Alamat
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ session('teacher_create.step1.alamat') ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            MATA PELAJARAN
        ====================================================== --}}

        <div
            class="mb-7 overflow-visible rounded-2xl border border-gray-200 bg-white"
        >

            <button
                type="button"
                class="flex w-full items-center justify-between p-7 text-left"
                onclick="toggleAccordion(
                    'mapel-content',
                    'mapel-icon'
                )"
            >

                <div class="flex items-center gap-3">

                    <span class="text-xl text-gray-900">
                        ▣
                    </span>

                    <span class="text-lg font-semibold text-gray-900">
                        Mata Pelajaran
                    </span>

                </div>


                <span
                    id="mapel-icon"
                    class="text-xl text-gray-900"
                >
                    ⌄
                </span>

            </button>


            <div
                id="mapel-content"
                class="hidden border-t border-gray-200 px-7 pb-7 pt-6"
            >

                <div class="space-y-3">

                    @foreach ($schoolMapels as $schoolMapel)

                        <div
                            class="rounded-lg bg-gray-50 px-4 py-3"
                        >

                            <span class="font-medium text-gray-900">

                                {{ $schoolMapel->masterMapel->name ?? '-' }}

                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- SECTION MAPEL --}}
        @foreach ($schoolMapels as $schoolMapel)
            @php
                $mapelId = $schoolMapel->id;
                $rombels = $rombelsByMapel[$mapelId] ?? collect();
            @endphp

            <div
                class="mapel-section relative mb-7 overflow-visible rounded-2xl border border-gray-200 bg-white p-7"
                data-mapel-id="{{ $mapelId }}"
            >

                {{-- HEADER MAPEL --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl text-gray-900">
                            ▱
                        </span>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Kelas -
                            {{ $schoolMapel->masterMapel->name ?? '-' }}
                        </h2>
                    </div>

                    {{-- TAMBAH --}}
                    <div class="relative">
                        <button
                            type="button"
                            class="add-class-btn inline-flex h-[52px] items-center gap-2 rounded-lg border border-gray-900 bg-white px-5 text-base font-medium text-gray-900 hover:bg-gray-50"
                            data-mapel-id="{{ $mapelId }}"
                        >
                            <span class="text-xl">
                                +
                            </span>

                            <span>
                                Tambah
                            </span>
                        </button>


                        {{-- DROPDOWN KELAS --}}
                        <div
                            class="class-picker absolute right-0 top-[60px] z-50 hidden w-72 rounded-xl border border-gray-200 bg-white p-2 shadow-xl"
                            data-mapel-id="{{ $mapelId }}"
                        >

                            <div class="class-options max-h-64 overflow-y-auto py-1">
                                @forelse ($rombels as $rombel)

                                    <button
                                        type="button"
                                        class="class-option flex w-full items-center rounded-lg px-3 py-3 text-left text-sm text-gray-900 hover:bg-gray-100"
                                        data-mapel-id="{{ $mapelId }}"
                                        data-rombel-id="{{ $rombel->id }}"
                                        data-rombel-name="{{ $rombel->jenjang }} {{ $rombel->schoolMajor->major->kode_jur ?? '-' }} {{ $rombel->name }}"
                                    >

                                        {{ $rombel->jenjang }}
                                        {{ $rombel->schoolMajor->major->kode_jur ?? '-' }}
                                        {{ $rombel->name }}

                                    </button>

                                @empty

                                    <div class="px-3 py-4 text-sm text-gray-500">
                                        Tidak ada kelas yang tersedia.
                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TABLE
                ================================================== --}}

                <div class="mt-5 border-t border-gray-300 pt-5">

                    {{-- HEADER --}}

                    <div
                        class="grid grid-cols-[140px_135px_minmax(0,1fr)_48px] gap-6 pb-4"
                    >

                        <div class="text-base font-medium text-gray-900">
                            Kelas
                        </div>

                        <div class="text-base font-medium text-gray-900">
                            Hari
                        </div>

                        <div class="text-base font-medium text-gray-900">
                            Jam Pelajaran
                        </div>

                        <div></div>

                    </div>


                    {{-- SCHEDULE LIST --}}

                    <div
                        class="schedule-list space-y-4"
                        data-mapel-id="{{ $mapelId }}"
                    >

                        {{-- Jadwal akan dibuat melalui JavaScript --}}

                    </div>

                </div>

            </div>

        @endforeach


        {{-- =====================================================
            BUTTON BAWAH
        ====================================================== --}}

        <div class="flex items-center justify-between">

            <a
                href="{{ route('teachers.create.step2') }}"
                class="inline-flex h-12 items-center rounded-lg border border-gray-900 bg-white px-5 text-base font-medium text-gray-900 hover:bg-gray-50"
            >
                Kembali
            </a>


            <button
                type="submit"
                class="inline-flex h-12 items-center rounded-lg bg-black px-7 text-base font-medium text-white hover:bg-gray-800"
            >
                Simpan
            </button>

        </div>

    </form>

</div>


{{-- =============================================================
    DATA UNTUK JAVASCRIPT
============================================================= --}}

@php

    /*
    |--------------------------------------------------------------------------
    | ROMBEL JSON
    |--------------------------------------------------------------------------
    */

    $rombelsJson = [];

    foreach ($rombelsByMapel as $mapelId => $rombels) {

        $rombelsJson[$mapelId] =
            $rombels
                ->map(function ($rombel) {

                    $majorName =
                        $rombel->schoolMajor->major->kode_jur
                        ?? '-';

                    return [

                        'id' =>
                            (int) $rombel->id,

                        'name' =>
                            $rombel->name,

                        'jenjang' =>
                            $rombel->jenjang,

                        'major' =>
                            $majorName,

                        'label' =>
                            $rombel->jenjang .
                            ' ' .
                            $majorName .
                            ' ' .
                            $rombel->name,

                    ];

                })
                ->values();

    }


    /*
    |--------------------------------------------------------------------------
    | LESSON PERIOD JSON
    |--------------------------------------------------------------------------
    */

    $lessonPeriodsJson =
        $lessonPeriods
            ->map(function ($period) {

                return [

                    'id' =>
                        (int) $period->id,

                    'jam_ke' =>
                        $period->jam_ke,

                    'mulai' =>
                        substr(
                            $period->jam_mulai,
                            0,
                            5
                        ),

                    'selesai' =>
                        substr(
                            $period->jam_selesai,
                            0,
                            5
                        ),

                ];

            })
            ->values();

@endphp


<script>

    /*
    |--------------------------------------------------------------------------
    | DATA DARI LARAVEL
    |--------------------------------------------------------------------------
    */

    const rombelsByMapel =
        @json($rombelsJson);

    const lessonPeriods =
        @json($lessonPeriodsJson);


    /*
    |--------------------------------------------------------------------------
    | TOGGLE ACCORDION
    |--------------------------------------------------------------------------
    */

    function toggleAccordion(
        contentId,
        iconId
    ) {

        const content =
            document.getElementById(
                contentId
            );

        const icon =
            document.getElementById(
                iconId
            );


        if (!content) {
            return;
        }


        content.classList.toggle(
            'hidden'
        );


        if (
            content.classList.contains(
                'hidden'
            )
        ) {

            icon.textContent = '⌄';

        } else {

            icon.textContent = '⌃';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | OPTION HARI
    |--------------------------------------------------------------------------
    */

    function getDayOptions()
    {

        return `

            <option value="">
                Pilih hari
            </option>

            <option value="senin">
                Senin
            </option>

            <option value="selasa">
                Selasa
            </option>

            <option value="rabu">
                Rabu
            </option>

            <option value="kamis">
                Kamis
            </option>

            <option value="jumat">
                Jumat
            </option>

            <option value="sabtu">
                Sabtu
            </option>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | OPTION JAM
    |--------------------------------------------------------------------------
    */

    function getPeriodOptions()
    {

        let html = `

            <option value="">
                Pilih jam
            </option>

        `;


        lessonPeriods.forEach(
            function(period) {

                html += `

                    <option value="${period.id}">

                        ${period.jam_ke}
                        (${period.mulai}-${period.selesai})

                    </option>

                `;

            }
        );


        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | TUTUP SEMUA DROPDOWN
    |--------------------------------------------------------------------------
    */

    function closeAllClassPickers()
    {

        document
            .querySelectorAll(
                '.class-picker'
            )
            .forEach(
                function(picker) {

                    picker.classList.add(
                        'hidden'
                    );

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | BUAT ROW JADWAL
    |--------------------------------------------------------------------------
    */

    function createScheduleRow(
        mapelId,
        rombelId,
        showClassName,
        rombelName
    ) {

        const classGroup =
            document.querySelector(
                `.class-group[data-mapel-id="${mapelId}"][data-rombel-id="${rombelId}"]`
            );


        /*
        |--------------------------------------------------------------------------
        | HITUNG INDEX
        |--------------------------------------------------------------------------
        */

        let index = 0;


        if (classGroup) {

            index =
                classGroup.querySelectorAll(
                    '.schedule-row'
                ).length;

        }


        /*
        |--------------------------------------------------------------------------
        | BARIS
        |--------------------------------------------------------------------------
        */

        const row =
            document.createElement(
                'div'
            );


        row.className =
            'schedule-row grid grid-cols-[140px_135px_minmax(0,1fr)_48px] items-center gap-6';


        row.dataset.index =
            index;


        /*
        |--------------------------------------------------------------------------
        | NAMA FIELD
        |--------------------------------------------------------------------------
        */

        const baseName =
            `schedules[${mapelId}][${rombelId}][${index}]`;


        /*
        |--------------------------------------------------------------------------
        | HTML KELAS
        |--------------------------------------------------------------------------
        */

        let classHtml = '';


        if (showClassName) {

            classHtml = `

                <div
                    class="class-name font-normal text-gray-900"
                >

                    ${rombelName}

                </div>

            `;

        } else {

            classHtml = `

                <div></div>

            `;

        }


        /*
        |--------------------------------------------------------------------------
        | HTML ROW
        |--------------------------------------------------------------------------
        */

        row.innerHTML = `

            ${classHtml}


            {{-- HARI --}}

            <div>

                <select
                    name="${baseName}[hari]"
                    required
                    class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                >

                    ${getDayOptions()}

                </select>

            </div>


            {{-- JAM --}}

            <div class="flex items-center gap-3">

                <select
                    name="${baseName}[lesson_period_start_id]"
                    required
                    class="h-[54px] min-w-0 flex-1 rounded-lg border border-gray-900 bg-white px-4 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                >

                    ${getPeriodOptions()}

                </select>


                <span class="shrink-0 text-gray-600">
                    -
                </span>


                <select
                    name="${baseName}[lesson_period_end_id]"
                    required
                    class="h-[54px] min-w-0 flex-1 rounded-lg border border-gray-900 bg-white px-4 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                >

                    ${getPeriodOptions()}

                </select>

            </div>


            {{-- HAPUS --}}

            <button
                type="button"
                class="delete-schedule flex h-[54px] w-12 items-center justify-center rounded-lg border border-gray-900 bg-white text-lg hover:bg-gray-50"
                title="Hapus jadwal"
            >

                🗑

            </button>

        `;


        return row;

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH KELAS / JADWAL
    |--------------------------------------------------------------------------
    */

    function addClassSchedule(
        mapelId,
        rombelId,
        rombelName
    ) {

        const list =
            document.querySelector(
                `.schedule-list[data-mapel-id="${mapelId}"]`
            );


        if (!list) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH GROUP KELAS SUDAH ADA
        |--------------------------------------------------------------------------
        */

        let classGroup =
            list.querySelector(
                `.class-group[data-mapel-id="${mapelId}"][data-rombel-id="${rombelId}"]`
            );


        /*
        |--------------------------------------------------------------------------
        | KELAS BELUM ADA
        |--------------------------------------------------------------------------
        */

        if (!classGroup) {

            classGroup =
                document.createElement(
                    'div'
                );


            classGroup.className =
                'class-group space-y-4';


            classGroup.dataset.mapelId =
                mapelId;


            classGroup.dataset.rombelId =
                rombelId;


            /*
            |--------------------------------------------------------------------------
            | ROW PERTAMA
            |--------------------------------------------------------------------------
            */

            const row =
                createScheduleRow(
                    mapelId,
                    rombelId,
                    true,
                    rombelName
                );


            classGroup.appendChild(
                row
            );


            list.appendChild(
                classGroup
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | KELAS SUDAH ADA
        |--------------------------------------------------------------------------
        |
        | Jangan tampilkan nama kelas lagi.
        | Hanya tambah hari + jam.
        |
        */

        const row =
            createScheduleRow(
                mapelId,
                rombelId,
                false,
                rombelName
            );


        classGroup.appendChild(
            row
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUTTON TAMBAH
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.add-class-btn'
        )
        .forEach(
            function(button) {

                button.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();


                        const mapelId =
                            this.dataset.mapelId;


                        const picker =
                            document.querySelector(
                                `.class-picker[data-mapel-id="${mapelId}"]`
                            );


                        if (!picker) {
                            return;
                        }


                        const isHidden =
                            picker.classList.contains(
                                'hidden'
                            );


                        closeAllClassPickers();


                        if (isHidden) {

                            picker.classList.remove(
                                'hidden'
                            );

                        }

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | PILIH KELAS
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.class-option'
        )
        .forEach(
            function(button) {

                button.addEventListener(
                    'click',
                    function(event) {

                        event.stopPropagation();


                        const mapelId =
                            this.dataset.mapelId;


                        const rombelId =
                            this.dataset.rombelId;


                        const rombelName =
                            this.dataset.rombelName;


                        /*
                        |--------------------------------------------------------------------------
                        | TAMBAHKAN JADWAL
                        |--------------------------------------------------------------------------
                        */

                        addClassSchedule(
                            mapelId,
                            rombelId,
                            rombelName
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | TUTUP DROPDOWN
                        |--------------------------------------------------------------------------
                        */

                        closeAllClassPickers();

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | HAPUS JADWAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function(event) {

            const deleteButton =
                event.target.closest(
                    '.delete-schedule'
                );


            if (!deleteButton) {
                return;
            }


            const row =
                deleteButton.closest(
                    '.schedule-row'
                );


            if (!row) {
                return;
            }


            const classGroup =
                row.closest(
                    '.class-group'
                );


            if (!classGroup) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | HAPUS ROW
            |--------------------------------------------------------------------------
            */

            row.remove();


            /*
            |--------------------------------------------------------------------------
            | JIKA TIDAK ADA JADWAL LAGI
            |--------------------------------------------------------------------------
            */

            const remainingRows =
                classGroup.querySelectorAll(
                    '.schedule-row'
                );


            if (
                remainingRows.length === 0
            ) {

                classGroup.remove();

            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE INDEX
            |--------------------------------------------------------------------------
            */

            reindexClassGroup(
                classGroup
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REINDEX FIELD
    |--------------------------------------------------------------------------
    */

    function reindexClassGroup(
        classGroup
    ) {

        if (!classGroup) {
            return;
        }


        const mapelId =
            classGroup.dataset.mapelId;


        const rombelId =
            classGroup.dataset.rombelId;


        const rows =
            classGroup.querySelectorAll(
                '.schedule-row'
            );


        rows.forEach(
            function(row, index) {

                const selects =
                    row.querySelectorAll(
                        'select'
                    );


                if (
                    selects.length !== 3
                ) {
                    return;
                }


                const baseName =
                    `schedules[${mapelId}][${rombelId}][${index}]`;


                selects[0].name =
                    `${baseName}[hari]`;


                selects[1].name =
                    `${baseName}[lesson_period_start_id]`;


                selects[2].name =
                    `${baseName}[lesson_period_end_id]`;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | KLIK DI LUAR DROPDOWN
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function(event) {

            if (
                event.target.closest(
                    '.add-class-btn'
                )
            ) {
                return;
            }


            if (
                event.target.closest(
                    '.class-picker'
                )
            ) {
                return;
            }


            closeAllClassPickers();

        }
    );

</script>

@endsection