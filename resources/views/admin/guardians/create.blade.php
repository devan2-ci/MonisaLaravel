@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Wali Siswa
        </h1>

        <p class="mt-1 text-gray-500">
            Tambahkan data wali untuk siswa.
        </p>

    </div>


    {{-- Validation Error --}}
    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="font-semibold text-red-700">
                Terjadi kesalahan:
            </div>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <form
        action="{{ route('guardians.store') }}"
        method="POST"
    >

        @csrf

        <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">

            {{-- Form Header --}}
            <div class="border-b border-gray-100 px-8 py-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi Wali Siswa
                </h2>

            </div>


            {{-- Form Body --}}
            <div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-2">


                {{-- Siswa --}}
                <div>

                    <label
                        for="student_id"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Siswa
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="student_id"
                        name="student_id"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            -- Pilih Siswa --
                        </option>

                        @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                {{ old('student_id') == $student->id ? 'selected' : '' }}
                            >
                                {{ $student->name }}
                                @if($student->nis)
                                    - {{ $student->nis }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                    @error('student_id')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Nama Wali --}}
                <div>

                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Nama Wali
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Santoso"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('name')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Hubungan --}}
                <div>

                    <label
                        for="relationship"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Hubungan dengan Siswa
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="relationship"
                        name="relationship"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option value="">
                            -- Pilih Hubungan --
                        </option>

                        <option
                            value="ayah"
                            {{ old('relationship') === 'ayah' ? 'selected' : '' }}
                        >
                            Ayah
                        </option>

                        <option
                            value="ibu"
                            {{ old('relationship') === 'ibu' ? 'selected' : '' }}
                        >
                            Ibu
                        </option>

                        <option
                            value="wali"
                            {{ old('relationship') === 'wali' ? 'selected' : '' }}
                        >
                            Wali
                        </option>

                    </select>

                    @error('relationship')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- No HP --}}
                <div>

                    <label
                        for="phone"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        No. HP
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('phone')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Contoh: wali@email.com"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('email')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- Alamat --}}
                <div class="md:col-span-2">

                    <label
                        for="address"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Alamat
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Masukkan alamat wali"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('address') }}</textarea>

                    @error('address')

                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


            </div>


            {{-- Footer --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a
                    href="{{ route('guardians.index') }}"
                    class="rounded-xl border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
                >
                    Simpan Wali
                </button>

            </div>

        </div>

    </form>

</div>

@endsection