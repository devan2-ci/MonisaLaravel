@extends('layout.app')

@section('content')

<div class="mb-6">

    <h1 class="text-2xl font-bold text-gray-800">
        Tambah Jurusan Sekolah
    </h1>

    <p class="mt-1 text-gray-500">
        Pilih jurusan yang tersedia di sekolah Anda.
    </p>

</div>


<form
    action="{{ route('school_majors.store') }}"
    method="POST"
>

    @csrf


    <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-8 py-5">

            <h2 class="text-lg font-semibold text-gray-800">
                Daftar Jurusan
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Centang satu atau beberapa jurusan.
            </p>

        </div>


        <div class="p-8">

            @if ($majors->count() > 0)

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach ($majors as $major)

                        @php
                            $isChecked = in_array(
                                $major->id,
                                $selectedMajorIds
                            );
                        @endphp


                        <label
                            for="major_{{ $major->id }}"
                            class="flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 p-4 transition hover:border-indigo-400 hover:bg-indigo-50/40"
                        >

                            <input
                                type="checkbox"
                                id="major_{{ $major->id }}"
                                name="major_ids[]"
                                value="{{ $major->id }}"
                                {{ $isChecked ? 'checked' : '' }}
                                class="mt-1 h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >


                            <div>

                                <p class="font-medium text-gray-800">
                                    {{ $major->name }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Kode: {{ $major->kode_jur }}
                                </p>

                            </div>

                        </label>

                    @endforeach

                </div>

            @else

                <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-5">

                    <p class="font-medium text-yellow-800">
                        Belum ada jurusan dari SuperAdmin.
                    </p>

                </div>

            @endif


            @error('major_ids')

                <p class="mt-4 text-sm text-red-500">
                    {{ $message }}
                </p>

            @enderror

        </div>


        <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

            <a
                href="{{ route('school_majors.index') }}"
                class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-gray-700 hover:bg-gray-100"
            >
                Batal
            </a>


            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
            >
                Simpan
            </button>

        </div>

    </div>

</form>

@endsection