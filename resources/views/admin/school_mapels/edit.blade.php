@extends('layout.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Edit Mata Pelajaran Sekolah
    </h1>

    <p class="mt-1 text-gray-500">
        Pilih mata pelajaran yang tersedia di sekolah Anda.
    </p>
</div>


<form
    action="{{ route('school_mapel.update', $school->id) }}"
    method="POST"
>
    @csrf
    @method('PUT')

    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

        {{-- Header --}}
        <div class="border-b border-gray-100 px-8 py-5">
            <h2 class="text-lg font-semibold text-gray-800">
                Daftar Mata Pelajaran
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Centang mata pelajaran yang tersedia di sekolah.
            </p>
        </div>


        {{-- Content --}}
        <div class="p-8">

            @if ($mapels->count() > 0)

                {{-- Pilih Semua --}}
                <div class="mb-6 flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">

                    <input
                        type="checkbox"
                        id="select_all"
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <label
                        for="select_all"
                        class="cursor-pointer font-medium text-gray-700"
                    >
                        Pilih Semua Mata Pelajaran
                    </label>

                </div>


                {{-- Daftar Mata Pelajaran --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    @foreach ($mapels as $mapel)

                        @php
                            $isChecked = in_array(
                                $mapel->id,
                                $selectedMapelIds
                            );
                        @endphp

                        <label
                            for="mapel_{{ $mapel->id }}"
                            class="flex items-start gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer transition
                            hover:border-indigo-400 hover:bg-indigo-50/40"
                        >

                            <input
                                type="checkbox"
                                id="mapel_{{ $mapel->id }}"
                                name="master_mapel_ids[]"
                                value="{{ $mapel->id }}"
                                {{ $isChecked ? 'checked' : '' }}
                                class="mapel-checkbox mt-1 h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >

                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $mapel->name }}
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Kode: {{ $mapel->kode_mapel }}
                                </p>
                            </div>

                        </label>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                    <p class="font-medium text-yellow-800">
                        Belum ada mata pelajaran tersedia.
                    </p>

                    <p class="text-sm text-yellow-700 mt-1">
                        Silakan hubungi SuperAdmin untuk menambahkan mata pelajaran
                        terlebih dahulu.
                    </p>

                </div>

            @endif


            {{-- Error --}}
            @error('master_mapel_ids')
                <p class="mt-4 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

            @error('master_mapel_ids.*')
                <p class="mt-4 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Footer --}}
        <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

            <a
                href="{{ route('school_mapel.index') }}"
                class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-gray-700 transition hover:bg-gray-100"
            >
                Batal
            </a>

            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white shadow-sm transition hover:bg-indigo-700"
            >
                Simpan Perubahan
            </button>

        </div>

    </div>

</form>


{{-- Select All Script --}}
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const selectAll = document.getElementById('select_all');

        const checkboxes = document.querySelectorAll('.mapel-checkbox');


        // Update status checkbox "Pilih Semua"
        function updateSelectAll() {

            const total = checkboxes.length;

            const checked = document.querySelectorAll(
                '.mapel-checkbox:checked'
            ).length;

            // Semua tercentang
            selectAll.checked = total > 0 && checked === total;

            // Sebagian tercentang
            selectAll.indeterminate =
                checked > 0 && checked < total;
        }


        // Klik "Pilih Semua"
        selectAll.addEventListener('change', function () {

            checkboxes.forEach(function (checkbox) {

                checkbox.checked = selectAll.checked;

            });

            selectAll.indeterminate = false;
        });


        // Jika checkbox mapel diubah satu per satu
        checkboxes.forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                updateSelectAll();

            });

        });


        // Cek kondisi awal saat halaman edit dibuka
        updateSelectAll();

    });

</script>

@endsection