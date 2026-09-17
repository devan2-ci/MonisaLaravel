@extends('layout.app')

@section('content')
    <div class="space-y-6, p-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between p-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Gallery Management
                </h1>
                <p class="text-sm text-gray-500">
                    Kelola seluruh galeri di sekolah
                </p>
            </div>

            <a href="{{ route('galleries.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">
                <span>+</span>
                Tambah Galeri
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
                            placeholder="Cari gallery..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="text-sm text-gray-500">
                        Total Gallery:
                        <span class="font-semibold text-gray-700">
                            {{ $galleries->count() }}
                        </span>
                    </div>

                </div>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>
                        <tr class="bg-gray-50">

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                #
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Name
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Photo
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($galleries as $index => $gallery)

                            <tr class="border-t border-gray-100 hover:bg-indigo-50/40 transition duration-200">

                                <td class="px-6 py-5 text-sm text-gray-600">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-6 py-5">

                                    <div>
                                        <div class="font-semibold text-gray-800">
                                            {{ ucfirst($gallery->name) }}
                                        </div>
                                    </div>

                                </td>

                                <td class="px-6 py-5">

                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        {{ $gallery->photo }}
                                    </span>

                                </td>

                                <td class="px-6 py-5">

                                    <div class="flex justify-center gap-2">

                                        <a href="{{ route('galleries.edit', $gallery->id) }}"
                                            class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">
                                            Edit
                                        </a>

                                        <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                onclick="return confirm('Yakin ingin menghapus galeri ini?')"
                                                class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="py-16 text-center">

                                    <div class="space-y-2">

                                        <div class="text-lg font-medium text-gray-500">
                                            Tidak ada data
                                        </div>

                                        <div class="text-sm text-gray-400">
                                            Belum ada galeri yang dibuat.
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