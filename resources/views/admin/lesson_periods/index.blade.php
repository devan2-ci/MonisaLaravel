@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Lesson Period Management
            </h1>

            <p class="text-sm text-gray-500">
                Kelola master jam pelajaran di sekolah
            </p>
        </div>

        <a href="{{ route('lesson-periods.create') }}"
            class="mt-4 md:mt-0 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">

            <span>+</span>
            Tambah Jam

        </a>
    </div>


    {{-- Success Alert --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        {{-- Search --}}
        <div class="border-b border-gray-100 p-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div class="relative w-full md:w-80">

                    <input
                        type="text"
                        placeholder="Cari jam pelajaran..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none">

                </div>

                <div class="text-sm text-gray-500">

                    Total Jam:
                    <span class="font-semibold text-gray-700">
                        {{ $lessonPeriods->count() }}
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
                            Jam Ke
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jam Mulai
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jam Selesai
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($lessonPeriods as $index => $period)

                        <tr class="border-t border-gray-100 transition duration-200 hover:bg-indigo-50/40">

                            {{-- No --}}
                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>


                            {{-- Jam Ke --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-800">
                                    Jam {{ $period->jam_ke }}
                                </div>

                            </td>


                            {{-- Jam Mulai --}}
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                    {{ \Carbon\Carbon::parse($period->jam_mulai)->format('H:i') }}
                                </span>

                            </td>


                            {{-- Jam Selesai --}}
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-700">
                                    {{ \Carbon\Carbon::parse($period->jam_selesai)->format('H:i') }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-5">

                                @if($period->is_active)

                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="px-6 py-5">

                                <div class="flex justify-center gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('lesson-periods.edit', $period->id) }}"
                                        class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">
                                        Edit
                                    </a>


                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('lesson-periods.destroy', $period->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Yakin ingin menghapus jam pelajaran ini?')"
                                            class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6" class="py-16 text-center">

                                <div class="space-y-2">

                                    <div class="text-lg font-medium text-gray-500">
                                        Tidak ada data
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Belum ada jam pelajaran yang dibuat.
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