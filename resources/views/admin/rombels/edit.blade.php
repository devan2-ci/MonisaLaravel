@extends('layout.app')

@section('content')

<div class="p-6">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Rombel
        </h1>

        <p class="mt-1 text-gray-500">
            Perbarui informasi rombongan belajar.
        </p>

    </div>


    <form
        action="{{ route('rombels.update', $rombel) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-8 py-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Rombel
                </h2>

            </div>


            <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">


                {{-- Jenjang --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jenjang Kelas
                    </label>

                    <select
                        name="jenjang"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                        <option value="10" {{ old('jenjang', $rombel->jenjang) == '10' ? 'selected' : '' }}>
                            Kelas 10
                        </option>

                        <option value="11" {{ old('jenjang', $rombel->jenjang) == '11' ? 'selected' : '' }}>
                            Kelas 11
                        </option>

                        <option value="12" {{ old('jenjang', $rombel->jenjang) == '12' ? 'selected' : '' }}>
                            Kelas 12
                        </option>

                    </select>

                    @error('jenjang')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Jurusan --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jurusan
                    </label>

                    <select
                        name="school_major_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                        @foreach($schoolMajors as $schoolMajor)

                            <option
                                value="{{ $schoolMajor->id }}"
                                {{ old('school_major_id', $rombel->school_major_id) == $schoolMajor->id ? 'selected' : '' }}
                            >
                                {{ $schoolMajor->major->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('school_major_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Name --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Kelas
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $rombel->name) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Tahun ajaran --}}
                <div>
                    <label
                        for="tahun_ajaran"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Tahun Ajaran
                    </label>

                    <input
                        type="text"
                        id="tahun_ajaran"
                        name="tahun_ajaran"
                        value="{{ old('tahun_ajaran', $rombel->tahun_ajaran ?? '') }}"
                        placeholder="Contoh: 2025/2026"
                        maxlength="9"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >

                    @error('tahun_ajaran')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Wali kelas --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Wali Kelas
                    </label>

                    <select
                        name="teacher_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ old('teacher_id', $rombel->teacher_id) == $teacher->id ? 'selected' : '' }}
                            >
                                {{ $teacher->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Status --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Status Rombel
                    </label>

                    <select
                        name="is_active"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                        <option
                            value="1"
                            {{ old('is_active', $rombel->is_active) == 1 ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="0"
                            {{ old('is_active', $rombel->is_active) == 0 ? 'selected' : '' }}
                        >
                            Tidak Aktif
                        </option>

                    </select>

                </div>


            </div>


            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a
                    href="{{ route('rombels.index') }}"
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