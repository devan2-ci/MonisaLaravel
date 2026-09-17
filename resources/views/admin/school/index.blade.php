@extends('layout.app')

@section('content')

    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Profil Sekolah
                </h1>

                <p class="text-gray-500 mt-1">
                    Informasi lengkap sekolah Anda.
                </p>
            </div>

            <a
                href="{{ route('my-school.edit', $school->id) }}"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-white shadow hover:bg-indigo-700">

                <i class="fa-solid fa-pen"></i>

                Edit Profil

            </a>

        </div>

        {{-- Card --}}
        <div class="rounded-2xl bg-white shadow border border-gray-100 overflow-hidden">

            {{-- Cover --}}
            <div class="h-40 bg-gradient-to-r from-indigo-600 to-blue-500"></div>

            <div class="px-8 pb-8">

                {{-- Logo --}}
                <div class="-mt-16 flex flex-col md:flex-row md:items-end gap-6">

                    <div
                        class="w-32 h-32 rounded-2xl border-4 border-white bg-gray-100 overflow-hidden shadow">

                        @if($school->photo)

                            <img
                                src="{{ asset('storage/'.$school->photo) }}"
                                class="w-full h-full object-cover">

                        @else

                            <div class="w-full h-full flex items-center justify-center">

                                <i class="fa-solid fa-school text-5xl text-gray-400"></i>

                            </div>

                        @endif

                    </div>

                    <div class="pb-2">

                        <h2 class="text-3xl font-bold text-gray-800">
                            {{ $school->nama }}
                        </h2>

                    </div>

                </div>

                {{-- Informasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">

                    <div class="rounded-xl border p-5">

                        <h3 class="font-semibold text-lg mb-4">
                            Informasi Umum
                        </h3>

                        <div class="space-y-4">

                            <div>

                                <label class="text-sm text-gray-500">
                                    Nama Sekolah
                                </label>

                                <div class="font-semibold">
                                    {{ $school->nama ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Kode Sekolah
                                </label>

                                <div class="font-semibold">
                                    {{ $school->kode_sekolah ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Email
                                </label>

                                <div>
                                    {{ $school->email ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Telepon
                                </label>

                                <div>
                                    {{ $school->no_telepon ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Website
                                </label>

                                <div>
                                    {{ $school->link_website ?? '-' }}
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="rounded-xl border p-5">

                        <h3 class="font-semibold text-lg mb-4">
                            Alamat Sekolah
                        </h3>

                        <div class="space-y-4">

                            <div>

                                <label class="text-sm text-gray-500">
                                    Provinsi
                                </label>

                                <div>
                                    {{ optional($school->province)->name ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Kota / Kabupaten
                                </label>

                                <div>
                                    {{ optional($school->city)->name ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Kecamatan
                                </label>

                                <div>
                                    {{ optional($school->district)->name ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Kelurahan
                                </label>

                                <div>
                                    {{ optional($school->village)->name ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Alamat Lengkap
                                </label>

                                <div>
                                    {{ $school->alamat_lengkap ?? '-' }}
                                </div>

                            </div>

                            <div>

                                <label class="text-sm text-gray-500">
                                    Kode Pos
                                </label>

                                <div>
                                    {{ $school->kode_pos ?? '-' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Lokasi --}}
                <div class="rounded-xl border p-5 mt-6">

                    <h3 class="font-semibold text-lg mb-4">
                        Lokasi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>

                            <label class="text-sm text-gray-500">
                                Latitude
                            </label>

                            <div>
                                {{ $school->latitude ?? '-' }}
                            </div>

                        </div>

                        <div>

                            <label class="text-sm text-gray-500">
                                Longitude
                            </label>

                            <div>
                                {{ $school->longitude ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection