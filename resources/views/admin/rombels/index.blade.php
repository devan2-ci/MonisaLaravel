@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Rombel
            </h1>

            <p class="mt-1 text-gray-500">
                Kelola rombongan belajar dan wali kelas.
            </p>
        </div>

        <a
            href="{{ route('rombels.create') }}"
            class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
        >
            + Tambah Rombel
        </a>

    </div>

    {{-- List --}}
    @if($rombels->count() > 0)

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

            @foreach($rombels as $rombel)

                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                    {{-- Nama kelas --}}
                    <div class="flex items-start justify-between">

                        <div>

                            <span class="text-sm font-medium text-indigo-600">
                                {{ $rombel->tahun_ajaran }}
                            </span>

                            <h2 class="mt-1 text-xl font-bold text-gray-800">
                                {{ $rombel->jenjang }}
                                {{ $rombel->schoolMajor->major->name }}
                                {{ $rombel->name }}
                            </h2>

                        </div>

                        @if($rombel->is_active)

                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                Aktif
                            </span>

                        @else

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                Tidak Aktif
                            </span>

                        @endif

                    </div>


                    {{-- Wali kelas --}}
                    <div class="mt-5">

                        <p class="text-xs text-gray-400">
                            Wali Kelas
                        </p>

                        <p class="mt-1 font-medium text-gray-700">
                            {{ $rombel->teacher->name }}
                        </p>

                    </div>


                    {{-- Jumlah siswa --}}
                    <div class="mt-4">

                        <p class="text-xs text-gray-400">
                            Jumlah Siswa
                        </p>

                        <p class="mt-1 font-medium text-gray-700">
                            {{ $rombel->students()->count() }} siswa
                        </p>

                    </div>


                    {{-- Action --}}
                    <div class="mt-6 flex gap-2">

                        <a
                            href="{{ route('rombels.students.index', $rombel) }}"
                            class="flex-1 rounded-xl bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-indigo-700"
                        >
                            Show
                        </a>

                        <a
                            href="{{ route('rombels.edit', $rombel) }}"
                            class="flex-1 rounded-xl bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-indigo-700"
                        >
                            Edit
                        </a>
                        
                    </div>


                    {{-- Delete --}}
                    <form
                        action="{{ route('rombels.destroy', $rombel) }}"
                        method="POST"
                        class="mt-2"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus rombel ini?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-500 transition hover:bg-red-50"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

            <h2 class="text-lg font-semibold text-gray-800">
                Belum ada rombel
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Silakan buat rombel terlebih dahulu.
            </p>

            <a
                href="{{ route('rombels.create') }}"
                class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700"
            >
                Tambah Rombel
            </a>

        </div>

    @endif

</div>

@endsection