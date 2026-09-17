@extends('layout.app')

@section('content')

<div class="space-y-6, p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Fasilitas
        </h1>
        <p class="text-gray-500">
            Tambahkan fasilitas baru ke dalam sistem.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="grid grid-cols-1 gap-6 p-8">
                
                {{-- Nama --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Fasilitas
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

                {{-- Foto --}}
                <div class="form-group mb-3">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Foto
                    </label>

                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                    <p class="text-xs text-gray-500 mt-2">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    <!-- error message untuk photo -->
                    @error('photo')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a href="{{ route('facilities.index') }}"
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