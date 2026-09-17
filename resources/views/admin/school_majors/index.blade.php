@extends('layout.app')

@section('content')

<div class="mb-6 flex items-center justify-between">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Jurusan
        </h1>

        <p class="mt-1 text-gray-500">
            Kelola jurusan yang tersedia di sekolah.
        </p>
    </div>

    @if ($schoolMajors->count() > 0)

        <a
            href="{{ route('school_majors.edit') }}"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
        >
            Edit Jurusan
        </a>

    @else

        <a
            href="{{ route('school_majors.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
        >
            Tambah Jurusan
        </a>

    @endif

</div>


@if (session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
        {{ session('success') }}
    </div>

@endif


@if ($schoolMajors->count() > 0)

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">

        @foreach ($schoolMajors as $schoolMajor)

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="mb-2">
                    <span class="text-sm font-semibold text-indigo-600">
                        {{ $schoolMajor->major->kode_jur }}
                    </span>
                </div>

                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $schoolMajor->major->name }}
                </h2>


                <div class="mt-6">

                    <form
                        action="{{ route('school_majors.destroy', $schoolMajor->id) }}"
                        method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-xl border border-red-200 px-5 py-2 text-sm font-medium text-red-500 transition hover:bg-red-50"
                        >
                            Hapus
                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

        <h2 class="text-lg font-semibold text-gray-800">
            Belum ada jurusan
        </h2>

        <p class="mt-2 text-sm text-gray-500">
            Silakan tambahkan jurusan yang tersedia di sekolah.
        </p>

        <a
            href="{{ route('school_majors.create') }}"
            class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700"
        >
            Tambah Jurusan
        </a>

    </div>

@endif

@endsection