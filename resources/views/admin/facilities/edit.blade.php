@extends('layout.app')

@section('content')

<div class="space-y-6, p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Fasilitas
        </h1>
        <p class="text-gray-500">
            Perbarui informasi fasilitas.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('facilities.update', $facility->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 p-8">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Fasilitas
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $facility->name) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

                    <div class="border-b border-gray-100 px-8 py-5">

                        <h2 class="text-lg font-semibold text-gray-800">
                            Foto Sekolah
                        </h2>

                    </div>

                    <div class="p-8">

                        @if($facility->image)

                            <div class="mb-4">

                                <p class="text-sm text-gray-500 mb-2">
                                    Foto saat ini
                                </p>

                                <img
                                    src="{{ asset('storage/' . $facility->image) }}"
                                    alt="{{ $facility->name }}"
                                    class="w-48 h-32 object-cover rounded-xl border"
                                >

                            </div>

                        @endif

                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Ganti Foto
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

                        @error('photo')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>
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