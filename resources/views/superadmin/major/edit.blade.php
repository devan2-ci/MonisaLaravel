@extends('layout.app')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Jurusan
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Edit jurusan yang sudah ada.
            </p>
        </div>

        <form action="{{ route('majors.update', $major->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                {{-- Nama Jurusan --}}
                <div>
                    <label class="block font-medium mb-2">
                        Nama Jurusan
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $major->name) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring focus:ring-blue-200">

                    @error('name')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Kode Jurusan --}}
                <div>
                    <label class="block font-medium mb-2">
                        Kede Jurusan
                    </label>

                    <input
                        type="text"
                        name="kode_jur"
                        value="{{ old('kode_jur', $major->kode_jur) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring focus:ring-blue-200">

                    @error('kode_jur')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-3">

                <a href="{{ route('majors.index') }}"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">

                    Batal

                </a>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection