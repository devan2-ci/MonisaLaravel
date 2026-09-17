@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Jadwal Mengajar
        </h1>

        <p class="mt-1 text-gray-500">
            Buat jadwal mengajar baru untuk guru di sekolah.
        </p>

    </div>


    {{-- Form --}}
    <form
        action="{{ route('teacher-schedules.store') }}"
        method="POST"
    >

        @csrf

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

            {{-- Form Header --}}
            <div class="border-b border-gray-100 px-8 py-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Jadwal Mengajar
                </h2>

            </div>


            {{-- Form Body --}}
            <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">


                {{-- GURU --}}
                <div>

                    <label
                        for="teacher_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Guru
                    </label>

                    <select
                        id="teacher_id"
                        name="teacher_id"
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
                                {{ $teacher->name }} - {{ $teacher->schoolMapel->masterMapel->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- KELAS / ROMBEL --}}
                <div>

                    <label
                        for="rombel_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Kelas / Rombel
                    </label>

                    <select
                        id="rombel_id"
                        name="rombel_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Pilih Kelas
                        </option>

                        @foreach($rombels as $rombel)

                            <option
                                value="{{ $rombel->id }}"
                                {{ old('rombel_id') == $rombel->id ? 'selected' : '' }}
                            >
                                {{ $rombel->jenjang }} - {{ $rombel->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('rombel_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- HARI --}}
                <div>

                    <label
                        for="hari"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Hari
                    </label>

                    <select
                        id="hari"
                        name="hari"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Pilih Hari
                        </option>

                        @foreach($hari as $item)

                            <option
                                value="{{ $item }}"
                                {{ old('hari') == $item ? 'selected' : '' }}
                            >
                                {{ ucfirst($item) }}
                            </option>

                        @endforeach

                    </select>

                    @error('hari')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- JAM MULAI --}}
                <div>

                    <label
                        for="lesson_period_start_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Jam Mulai
                    </label>

                    <select
                        id="lesson_period_start_id"
                        name="lesson_period_start_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Pilih Jam Mulai
                        </option>

                        @foreach($lessonPeriods as $period)

                            <option
                                value="{{ $period->id }}"
                                {{ old('lesson_period_start_id') == $period->id ? 'selected' : '' }}
                            >
                                Jam {{ $period->jam_ke }}
                                ({{ \Carbon\Carbon::parse($period->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($period->jam_selesai)->format('H:i') }})
                            </option>

                        @endforeach

                    </select>

                    @error('lesson_period_start_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- JAM SELESAI --}}
                <div>

                    <label
                        for="lesson_period_end_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Sampai Jam Ke
                    </label>

                    <select
                        id="lesson_period_end_id"
                        name="lesson_period_end_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            Pilih Jam Selesai
                        </option>

                        @foreach($lessonPeriods as $period)

                            <option
                                value="{{ $period->id }}"
                                {{ old('lesson_period_end_id') == $period->id ? 'selected' : '' }}
                            >
                                Jam {{ $period->jam_ke }}
                                ({{ \Carbon\Carbon::parse($period->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($period->jam_selesai)->format('H:i') }})
                            </option>

                        @endforeach

                    </select>

                    @error('lesson_period_end_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- STATUS --}}
                <div>

                    <label
                        for="is_active"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Status Jadwal
                    </label>

                    <select
                        id="is_active"
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="1"
                            {{ old('is_active', 1) == 1 ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="0"
                            {{ old('is_active') === '0' ? 'selected' : '' }}
                        >
                            Tidak Aktif
                        </option>

                    </select>

                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


            </div>


            {{-- Footer --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a
                    href="{{ route('teacher-schedules.index') }}"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
                >
                    Simpan Jadwal
                </button>

            </div>

        </div>

    </form>

</div>

@endsection