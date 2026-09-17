@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Sekolah
        </h1>

        <p class="text-gray-500">
            Tambahkan data sekolah baru ke dalam sistem.
        </p>
    </div>

    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

        <form action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="p-8 space-y-8">

                <div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                        <div>
                            <label class="block mb-2 font-medium">
                                Kode Sekolah
                            </label>

                            <input
                                type="text"
                                name="kode_sekolah"
                                value="{{ old('kode_sekolah') }}"
                                maxlength="8"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3">

                            @error('kode_sekolah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 font-medium">
                                Nama Sekolah
                            </label>

                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3">

                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>

            </div>

            <div class="border-t border-gray-100 px-8 py-5 flex justify-end gap-3">

                <a
                    href="{{ route('schools.index') }}"
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