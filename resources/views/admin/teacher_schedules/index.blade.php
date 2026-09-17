@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Jadwal Mengajar Guru
            </h1>

            <p class="text-sm text-gray-500">
                Kelola jadwal mengajar guru di sekolah
            </p>
        </div>

        <a href="{{ route('teacher-schedules.create') }}"
            class="mt-4 md:mt-0 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">

            <span>+</span>
            Tambah Jadwal

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
                        placeholder="Cari jadwal..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none">

                </div>

                <div class="text-sm text-gray-500">

                    Total Jadwal:
                    <span class="font-semibold text-gray-700">
                        {{ $schedules->count() }}
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
                            Hari
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Kelas
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Mapel
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Guru
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jam
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

                    @forelse($schedules as $schedule)

                        <tr class="border-t border-gray-100 transition duration-200 hover:bg-indigo-50/40">

                            {{-- No --}}
                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $loop->iteration }}
                            </td>


                            {{-- Hari --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold capitalize text-gray-800">
                                    {{ $schedule->hari }}
                                </div>

                            </td>


                            {{-- Kelas --}}
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                                    {{ $schedule->rombel->jenjang ?? '-' }} {{ $schedule->rombel->schoolMajor->major->kode_jur}} {{ $schedule->rombel->name}}
                                </span>

                            </td>


                            {{-- Mapel --}}
                            <td class="px-6 py-5">

                                <div class="font-medium text-gray-800">
                                    {{ $schedule->schoolMapel->masterMapel->name ?? '-' }}
                                </div>

                            </td>


                            {{-- Guru --}}
                            <td class="px-6 py-5">

                                <div class="font-medium text-gray-800">
                                    {{ $schedule->teacher->name ?? '-' }}
                                </div>

                            </td>


                            {{-- Jam --}}
                            <td class="px-6 py-5">

                                <div class="font-medium text-gray-800">

                                    Jam
                                    {{ $schedule->lessonPeriodStart->jam_ke ?? '-' }}

                                    -

                                    Jam
                                    {{ $schedule->lessonPeriodEnd->jam_ke ?? '-' }}

                                </div>

                                <div class="mt-1 text-xs text-gray-500">

                                    @if($schedule->lessonPeriodStart && $schedule->lessonPeriodEnd)

                                        {{ \Carbon\Carbon::parse($schedule->lessonPeriodStart->jam_mulai)->format('H:i') }}

                                        -

                                        {{ \Carbon\Carbon::parse($schedule->lessonPeriodEnd->jam_selesai)->format('H:i') }}

                                    @else

                                        -

                                    @endif

                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-5">

                                @if($schedule->is_active)

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
                                    <a href="{{ route('teacher-schedules.edit', $schedule->id) }}"
                                        class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">

                                        Edit

                                    </a>


                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('teacher-schedules.destroy', $schedule->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')"
                                            class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="9" class="py-16 text-center">

                                <div class="space-y-2">

                                    <div class="text-lg font-medium text-gray-500">
                                        Tidak ada data
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Belum ada jadwal mengajar yang dibuat.
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