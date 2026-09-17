@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Siswa
        </h1>

        <p class="mt-1 text-gray-500">
            Pilih siswa yang akan dimasukkan ke
            <strong>
                Kelas {{ $rombel->name }}
            </strong>.
        </p>

    </div>


    {{-- Error --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 px-5 py-4 text-red-600">

            <ul class="list-disc pl-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('rombels.students.store', $rombel) }}"
        method="POST"
    >

        @csrf


        @if($students->count() > 0)

            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b bg-gray-50 px-6 py-4">

                    <h2 class="font-semibold text-gray-800">
                        Daftar Siswa
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Centang siswa yang ingin dimasukkan ke rombel.
                    </p>

                </div>


                <div class="divide-y divide-gray-100">

                    @foreach($students as $student)

                        <label class="flex cursor-pointer items-center gap-4 px-6 py-4 hover:bg-gray-50">

                            <input
                                type="checkbox"
                                name="student_ids[]"
                                value="{{ $student->id }}"
                                class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >

                            <div>

                                <p class="font-medium text-gray-800">
                                    {{ $student->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    NIS: {{ $student->nis ?? '-' }}
                                    •
                                    NISN: {{ $student->nisn ?? '-' }}
                                </p>

                            </div>

                        </label>

                    @endforeach

                </div>

            </div>


            <div class="mt-6 flex justify-end gap-3">

                <a
                    href="{{ route('rombels.students.index', $rombel) }}"
                    class="rounded-xl border border-gray-200 px-5 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Masukkan ke Rombel
                </button>

            </div>

        @else

            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

                <h2 class="text-lg font-semibold text-gray-800">
                    Tidak ada siswa yang tersedia
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Semua siswa sudah memiliki rombel.
                </p>

                <a
                    href="{{ route('rombels.students.index', $rombel) }}"
                    class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Kembali
                </a>

            </div>

        @endif

    </form>

</div>

@endsection