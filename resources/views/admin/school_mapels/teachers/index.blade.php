@extends('layout.app')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>

            

            <h1 class="text-2xl font-bold text-gray-800">
                Guru Mata Pelajaran
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $schoolMapel->masterMapel->name ?? '-' }}
            </p>

        </div>


       <div class="flex gap-3">
            <a
                href="{{ route('school_mapel.teachers.create', $schoolMapel->id) }}"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                + Tambah
            </a>

            <a
                href="{{ route('school_mapel.index') }}"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
            >
                Kembali
            </a>
       </div>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 px-5 py-4 text-sm text-red-600">

            <ul class="list-inside list-disc">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Data Guru --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <h2 class="font-semibold text-gray-800">
                Daftar Guru
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Guru yang mengajar mata pelajaran ini.
            </p>

        </div>


        @if($teachers->count())

            <div class="divide-y divide-gray-100">

                @foreach($teachers as $teacher)

                    <div class="flex items-center justify-between px-6 py-4">

                        <div class="flex items-center gap-4">

                            {{-- Foto --}}
                            @if($teacher->photo)

                                <img
                                    src="{{ asset('storage/' . $teacher->photo) }}"
                                    alt="{{ $teacher->name }}"
                                    class="h-11 w-11 rounded-full object-cover"
                                >

                            @else

                                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-50 font-semibold text-indigo-600">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>

                            @endif


                            {{-- Informasi --}}
                            <div>

                                <p class="font-medium text-gray-800">
                                    {{ $teacher->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    NIP:
                                    {{ $teacher->nip ?? '-' }}
                                </p>

                            </div>

                        </div>


                        {{-- Lepas --}}
                        <form
                            action="{{ route(
                                'school_mapel.teachers.destroy',
                                [
                                    $schoolMapel->id,
                                    $teacher->id
                                ]
                            ) }}"
                            method="POST"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Lepaskan guru ini dari mata pelajaran?')"
                                class="rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                Lepas
                            </button>

                        </form>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <p class="text-gray-500">
                    Belum ada guru yang mengajar mata pelajaran ini.
                </p>

                <a
                    href="{{ route('school_mapel.teachers.create', $schoolMapel->id) }}"
                    class="mt-4 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-700"
                >
                    + Tambahkan guru
                </a>

            </div>

        @endif

    </div>

</div>

@endsection