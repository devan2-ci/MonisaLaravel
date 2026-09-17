@extends('layout.app')

@section('content')

<div class="space-y-6, p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Siswa
        </h1>
        <p class="text-gray-500">
            Tambahkan siswa baru ke dalam sistem.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="grid grid-cols-1 gap-6 p-8">

                {{-- Nama --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nis --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nis
                    </label>

                    <input
                        type="text"
                        name="nis"
                        value="{{ old('nis') }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('nis')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nisn --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nisn
                    </label>

                    <input
                        type="text"
                        name="nisn"
                        value="{{ old('nisn') }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('nisn')
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

                        <option value="l" {{ old('gender') == 'l' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="p" {{ old('gender') == 'p' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>

                    @error('gender')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a href="{{ route('students.index') }}"
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