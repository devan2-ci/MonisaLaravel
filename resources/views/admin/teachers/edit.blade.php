@extends('layout.app')

@section('content')

<div class="space-y-6, p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Guru
        </h1>
        <p class="text-gray-500">
            Perbarui informasi guru.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 p-8">

                {{-- Nama --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Guru
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $teacher->name) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nip --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nip
                    </label>

                    <input
                        type="text"
                        name="nip"
                        value="{{ old('nip', $teacher->nip) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('nip')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nuptk --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nuptk
                    </label>

                    <input
                        type="text"
                        name="nuptk"
                        value="{{ old('nuptk', $teacher->nuptk) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('nuptk')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="gender"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Pilih jenis kelamin</option>

                        <option value="l" {{ old('gender', $teacher->gender) == 'l' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="p" {{ old('gender', $teacher->gender) == 'p' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>

                    @error('gender')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Mata Pelajaran --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="school_mapel_id"
                        id="school_mapel_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="">
                            Pilih Mata Pelajaran
                        </option>

                        @foreach ($schoolMapels as $schoolMapel)
                            <option
                                value="{{ $schoolMapel->id }}"
                                @selected(old('school_mapel_id', $teacher->school_mapel_id) == $schoolMapel->id)
                            >
                                {{ $schoolMapel->masterMapel->kode_mapel }}
                                - 
                                {{ $schoolMapel->masterMapel->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('school_mapel_id')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a href="{{ route('teachers.index') }}"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 hover:bg-gray-100">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection