@extends('layout.app')

@section('content')
<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between p-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                School Management
            </h1>

            <p class="text-sm text-gray-500">
                Kelola data sekolah.
            </p>
        </div>

        <a href="{{ route('schools.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">

            <span>+</span>
            Tambah Sekolah

        </a>

    </div>

    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm border border-gray-100">

        {{-- Search --}}
        <div class="border-b border-gray-100 p-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div class="relative w-full md:w-80">

                    <input
                        type="text"
                        placeholder="Cari sekolah..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none">

                </div>

                <div class="text-sm text-gray-500">

                    Total Sekolah :

                    <span class="font-semibold text-gray-700">
                        {{ $schools->count() }}
                    </span>

                </div>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>

                    <tr class="bg-gray-50">

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            #
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kode Sekolah
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Nama Sekolah
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Alamat
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Provinsi
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kota/Kabupaten
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kecamatan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kelurahan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Kode Pos
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Latitude
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                            Longitude
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($schools as $index => $school)

                        <tr class="border-t border-gray-100 hover:bg-indigo-50/40 transition">

                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-6 py-5">

                                <div>

                                    <div class="font-semibold text-gray-800">
                                        {{ $school->kode_sekolah }}
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        Sekolah ID #{{ $school->id }}
                                    </div>

                                </div>

                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->nama }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->alamat_lengkap }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->provinsi }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->kota_kabupaten }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->kecamatan }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->kelurahan }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->kode_pos }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->latitude }}
                            </td>

                            <td class="px-6 py-5 text-sm text-gray-700">
                                {{ $school->longitude }}
                            </td>

                            <td class="px-6 py-5">

                                <div class="flex justify-center gap-2">

                                    <a
                                        href="{{ route('schools.edit',$school->id) }}"
                                        class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100">

                                        Edit

                                    </a>

                                    <form
                                        action="{{ route('schools.destroy',$school->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Yakin ingin menghapus sekolah ini?')"
                                            class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-100">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="py-20 text-center">

                                <div class="space-y-2">

                                    <div class="text-lg font-medium text-gray-500">
                                        Tidak ada data Sekolah
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Belum ada sekolah yang terdaftar.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
@endsection