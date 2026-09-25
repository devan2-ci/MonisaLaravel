@extends('layout.app')

@section('content')

<div class="container-fluid">

    <div class="mb-6">

        <a
            href="{{ route('school_mapel.teachers', $schoolMapel->id) }}"
            class="mb-3 inline-flex text-sm text-gray-500 hover:text-indigo-600"
        >
            ← Kembali
        </a>

        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Guru
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Pilih guru untuk mengajar
            <strong>
                {{ $schoolMapel->masterMapel->name ?? '-' }}
            </strong>.
        </p>

    </div>


    <form
        action="{{ route(
            'school_mapel.teachers.store',
            $schoolMapel->id
        ) }}"
        method="POST"
    >

        @csrf


        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="p-6">

                <label
                    for="teacher_id"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Nama Guru
                </label>


                <select
                    name="teacher_id"
                    id="teacher_id"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                >

                    <option value="">
                        Pilih Guru
                    </option>

                    @foreach($teachers as $teacher)

                        <option
                            value="{{ $teacher->id }}"
                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                        >
                            {{ $teacher->name }}
                            @if($teacher->nip)
                                — NIP {{ $teacher->nip }}
                            @endif
                        </option>

                    @endforeach

                </select>


                @error('teacher_id')

                    <p class="mt-2 text-sm text-red-500">
                        {{ $message }}
                    </p>

                @enderror


                @if($teachers->isEmpty())

                    <div class="mt-4 rounded-xl bg-yellow-50 px-4 py-3 text-sm text-yellow-700">
                        Semua guru di sekolah sudah terdaftar pada mata pelajaran ini.
                    </div>

                @endif

            </div>


            <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4">

                <a
                    href="{{ route(
                        'school_mapel.teachers',
                        $schoolMapel->id
                    ) }}"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    @disabled($teachers->isEmpty())
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Tambahkan
                </button>

            </div>

        </div>

    </form>

</div>

@endsection