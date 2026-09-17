@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Jam Pelajaran
        </h1>

        <p class="mt-1 text-gray-500">
            Perbarui informasi jam pelajaran sekolah.
        </p>

    </div>


    {{-- Form --}}
    <form
        action="{{ route('lesson-periods.update', $lessonPeriod) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

            {{-- Form Header --}}
            <div class="border-b border-gray-100 px-8 py-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Jam Pelajaran
                </h2>

            </div>


            {{-- Form Body --}}
            <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">


                {{-- Jam Ke --}}
                <div>

                    <label
                        for="jam_ke"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Jam Ke
                    </label>

                    <input
                        type="number"
                        id="jam_ke"
                        name="jam_ke"
                        min="1"
                        value="{{ old('jam_ke', $lessonPeriod->jam_ke) }}"
                        placeholder="Contoh: 1"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <p class="mt-1 text-xs text-gray-400">
                        Masukkan urutan jam pelajaran, misalnya 1, 2, 3, dan seterusnya.
                    </p>

                    @error('jam_ke')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Jam Mulai --}}
                <div>

                    <label
                        for="jam_mulai"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        id="jam_mulai"
                        name="jam_mulai"
                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($lessonPeriod->jam_mulai)->format('H:i')) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('jam_mulai')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Jam Selesai --}}
                <div>

                    <label
                        for="jam_selesai"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        id="jam_selesai"
                        name="jam_selesai"
                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($lessonPeriod->jam_selesai)->format('H:i')) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('jam_selesai')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Status --}}
                <div>

                    <label
                        for="is_active"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Status
                    </label>

                    <select
                        id="is_active"
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="1"
                            {{ old('is_active', $lessonPeriod->is_active) == 1 ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="0"
                            {{ old('is_active', $lessonPeriod->is_active) == 0 ? 'selected' : '' }}
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
                    href="{{ route('lesson-periods.index') }}"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

</div>

@endsection