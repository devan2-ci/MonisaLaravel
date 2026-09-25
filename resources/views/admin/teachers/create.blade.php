@extends('layout.app')

@section('content')

<div class="w-full px-6 py-8 lg:px-10">

    {{-- ========================================================= --}}
    {{-- ERROR VALIDATION --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if (session('success'))

        <div class="mb-7 rounded-2xl border border-green-200 bg-green-50 p-5">

            <p class="text-sm font-medium text-green-700">
                {{ session('success') }}
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if (session('error'))

        <div class="mb-7 rounded-2xl border border-red-200 bg-red-50 p-5">

            <p class="text-sm font-medium text-red-700">
                {{ session('error') }}
            </p>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- LANGKAH 1 --}}
    {{-- ========================================================= --}}

    <div class="mb-7 rounded-2xl border border-gray-200 bg-white p-7">

        <div class="flex items-center gap-3">

            <div class="h-7 w-7 rounded-full border-[6px] border-gray-300 border-r-gray-900">
            </div>

            <h1 class="text-2xl font-semibold text-gray-900">
                Langkah 1
            </h1>

        </div>


        <div class="mt-5 border-t border-gray-300 pt-5">

            <p class="text-base text-gray-800">
                Tambahkan data guru ke dalam sistem.
            </p>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('teachers.create.step1.store') }}"
        method="POST"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- DATA GURU --}}
        {{-- ===================================================== --}}

        <div class="mb-7 rounded-2xl border border-gray-200 bg-white p-7">

            <div class="mb-7 flex items-center gap-3 border-b border-gray-300 pb-5">

                <span class="text-xl text-gray-900">
                    ♙
                </span>

                <h2 class="text-lg font-semibold text-gray-900">
                    Data Guru
                    <span class="text-red-500">*</span>
                </h2>

            </div>


            <div class="grid grid-cols-1 gap-x-9 gap-y-6 lg:grid-cols-2">


                {{-- ================================================= --}}
                {{-- NAMA --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        Nama Guru
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama guru"
                        required
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('name')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- NIP --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        NIP
                    </label>

                    <input
                        type="text"
                        name="nip"
                        value="{{ old('nip') }}"
                        placeholder="Masukkan NIP"
                        required
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('nip')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- NUPTK --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        NUPTK
                    </label>

                    <input
                        type="text"
                        name="nuptk"
                        value="{{ old('nuptk') }}"
                        placeholder="Masukkan NUPTK"
                        required
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('nuptk')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- JENIS KELAMIN --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        Jenis Kelamin
                    </label>

                    <select
                        name="gender"
                        required
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                        <option value="">
                            Select
                        </option>

                        <option
                            value="l"
                            @selected(old('gender') === 'l')
                        >
                            Laki-laki
                        </option>

                        <option
                            value="p"
                            @selected(old('gender') === 'p')
                        >
                            Perempuan
                        </option>

                    </select>

                    @error('gender')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TANGGAL LAHIR --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('tanggal_lahir')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ALAMAT --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        Alamat
                    </label>

                    <input
                        type="text"
                        name="alamat"
                        value="{{ old('alamat') }}"
                        placeholder="Masukkan alamat"
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('alamat')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- EMAIL --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        E-Mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email"
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('email')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- NO HP --}}
                {{-- ================================================= --}}

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">
                        No. Handphone
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        placeholder="Masukkan nomor handphone"
                        class="h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                    @error('no_hp')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MATA PELAJARAN --}}
        {{-- ===================================================== --}}

        <div class="mb-7 rounded-2xl border border-gray-200 bg-white p-7">

            <div class="mb-7 flex items-center gap-3 border-b border-gray-300 pb-5">

                <span class="text-xl">
                    ▣
                </span>

                <h2 class="text-lg font-semibold text-gray-900">
                    Mata Pelajaran
                </h2>

            </div>


            <div
                id="mapel-list"
                class="space-y-5"
            >

                {{-- ================================================= --}}
                {{-- MAPEL PERTAMA --}}
                {{-- ================================================= --}}

                <div
                    class="mapel-row grid grid-cols-[minmax(0,1fr)_48px] items-end gap-3"
                >

                    <div>

                        <label class="mb-2 block text-base font-semibold text-gray-900">

                            <span class="mapel-number">
                                Mata pelajaran 1
                            </span>

                            <span class="text-red-500">
                                *
                            </span>

                        </label>


                        <select
                            name="mapel_ids[]"
                            required
                            class="mapel-select h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                        >

                            <option value="">
                                Select
                            </option>


                            @foreach($schoolMapels as $schoolMapel)

                                <option
                                    value="{{ $schoolMapel->id }}"
                                    @selected(
                                        old('mapel_ids.0') == $schoolMapel->id
                                    )
                                >
                                    {{ $schoolMapel->masterMapel->name ?? '-' }}
                                </option>

                            @endforeach

                        </select>


                        @error('mapel_ids')

                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- DELETE --}}

                    <button
                        type="button"
                        class="delete-mapel flex h-[54px] w-12 items-center justify-center rounded-lg border border-gray-900 bg-white text-lg hover:bg-gray-50"
                        style="visibility: hidden;"
                        title="Hapus"
                    >
                        🗑
                    </button>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TAMBAH MAPEL --}}
            {{-- ================================================= --}}

            <button
                type="button"
                id="add-mapel"
                class="mt-5 inline-flex h-12 items-center gap-2 rounded-lg border border-gray-900 bg-white px-5 text-base font-medium text-gray-900 hover:bg-gray-50"
            >

                <span class="text-xl">
                    +
                </span>

                <span>
                    Tambah
                </span>

            </button>

        </div>


        {{-- ===================================================== --}}
        {{-- SELANJUTNYA --}}
        {{-- ===================================================== --}}

        <div class="flex justify-end">

            <button
                type="submit"
                class="text-base font-medium text-gray-900 hover:underline"
            >
                Selanjutnya
            </button>

        </div>

    </form>

</div>


{{-- ============================================================= --}}
{{-- DATA MAPEL UNTUK JAVASCRIPT --}}
{{-- ============================================================= --}}

@php

    $mapelOptions = $schoolMapels
        ->map(function ($schoolMapel) {

            return [
                'id' => (int) $schoolMapel->id,

                'name' =>
                    $schoolMapel->masterMapel->name ?? '-',
            ];

        })
        ->values();

@endphp


<script>

    /*
    |--------------------------------------------------------------------------
    | DATA MAPEL
    |--------------------------------------------------------------------------
    */

    const mapelOptions =
        @json($mapelOptions);


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const mapelList =
        document.getElementById(
            'mapel-list'
        );

    const addMapelButton =
        document.getElementById(
            'add-mapel'
        );


    /*
    |--------------------------------------------------------------------------
    | BUAT OPTION MAPEL
    |--------------------------------------------------------------------------
    */

    function getMapelOptions()
    {

        let html = `

            <option value="">
                Select
            </option>

        `;


        mapelOptions.forEach(
            function(mapel)
            {

                html += `

                    <option value="${mapel.id}">
                        ${mapel.name}
                    </option>

                `;

            }
        );


        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE NOMOR MAPEL
    |--------------------------------------------------------------------------
    */

    function updateMapelNumbers()
    {

        const rows =
            mapelList.querySelectorAll(
                '.mapel-row'
            );


        rows.forEach(
            function(row, index)
            {

                const number =
                    row.querySelector(
                        '.mapel-number'
                    );


                if (number) {

                    number.textContent =
                        `Mata pelajaran ${index + 1}`;

                }


                const deleteButton =
                    row.querySelector(
                        '.delete-mapel'
                    );


                if (!deleteButton) {
                    return;
                }


                if (rows.length === 1) {

                    deleteButton.style.visibility =
                        'hidden';

                } else {

                    deleteButton.style.visibility =
                        'visible';

                }

            }
        );


        updateDuplicateOptions();

    }


    /*
    |--------------------------------------------------------------------------
    | CEGAH MAPEL DUPLIKAT
    |--------------------------------------------------------------------------
    */

    function updateDuplicateOptions()
    {

        const selects =
            mapelList.querySelectorAll(
                '.mapel-select'
            );


        const selectedValues = [];


        selects.forEach(
            function(select)
            {

                if (select.value) {

                    selectedValues.push(
                        select.value
                    );

                }

            }
        );


        selects.forEach(
            function(select)
            {

                Array.from(
                    select.options
                ).forEach(
                    function(option)
                    {

                        if (!option.value) {
                            return;
                        }


                        option.disabled =
                            selectedValues.includes(
                                option.value
                            )
                            &&
                            option.value !==
                                select.value;

                    }
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH MAPEL
    |--------------------------------------------------------------------------
    */

    addMapelButton.addEventListener(
        'click',
        function()
        {

            const row =
                document.createElement(
                    'div'
                );


            row.className =
                'mapel-row grid grid-cols-[minmax(0,1fr)_48px] items-end gap-3';


            row.innerHTML = `

                <div>

                    <label class="mb-2 block text-base font-semibold text-gray-900">

                        <span class="mapel-number">
                            Mata pelajaran
                        </span>

                        <span class="text-red-500">
                            *
                        </span>

                    </label>


                    <select
                        name="mapel_ids[]"
                        required
                        class="mapel-select h-[54px] w-full rounded-lg border border-gray-900 bg-white px-4 text-base text-gray-900 outline-none focus:ring-2 focus:ring-gray-200"
                    >

                        ${getMapelOptions()}

                    </select>

                </div>


                <button
                    type="button"
                    class="delete-mapel flex h-[54px] w-12 items-center justify-center rounded-lg border border-gray-900 bg-white text-lg hover:bg-gray-50"
                    title="Hapus"
                >
                    🗑
                </button>

            `;


            mapelList.appendChild(
                row
            );


            updateMapelNumbers();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | HAPUS MAPEL
    |--------------------------------------------------------------------------
    */

    mapelList.addEventListener(
        'click',
        function(event)
        {

            const button =
                event.target.closest(
                    '.delete-mapel'
                );


            if (!button) {
                return;
            }


            const row =
                button.closest(
                    '.mapel-row'
                );


            if (!row) {
                return;
            }


            row.remove();


            updateMapelNumbers();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PERUBAHAN MAPEL
    |--------------------------------------------------------------------------
    */

    mapelList.addEventListener(
        'change',
        function(event)
        {

            if (
                event.target.classList.contains(
                    'mapel-select'
                )
            ) {

                updateDuplicateOptions();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    updateMapelNumbers();

</script>

@endsection