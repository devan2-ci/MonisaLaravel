@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Data Presensi
            </h1>

            <p class="text-gray-500 mt-1">
                Kelola seluruh data kehadiran siswa.
            </p>
        </div>

        <a href="{{ route('attendances.create') }}"
           class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
            + Tambah Presensi
        </a>

    </div>


    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-100 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif


    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b">
                    <tr>

                        <th class="px-6 py-4 text-left">
                            #
                        </th>

                        <th class="px-6 py-4 text-left">
                            Siswa
                        </th>

                        <th class="px-6 py-4 text-left">
                            Rombel
                        </th>

                        <th class="px-6 py-4 text-left">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left">
                            Jam
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left">
                            Lampiran
                        </th>

                        <th class="px-6 py-4 text-left">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y">

                    @forelse($attendances as $attendance)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $attendance->student->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $attendance->rombel->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $attendance->jam }}
                            </td>

                            <td class="px-6 py-4">

                                @if($attendance->status === 'hadir')

                                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                        Hadir
                                    </span>

                                @elseif($attendance->status === 'izin')

                                    <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                        Izin
                                    </span>

                                @elseif($attendance->status === 'sakit')

                                    <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                        Sakit
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Alpha
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                @if($attendance->lampiran)

                                    <a href="{{ asset('storage/' . $attendance->lampiran) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline">
                                        Lihat
                                    </a>

                                @else

                                    <span class="text-gray-400">
                                        Tidak ada
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex gap-2">

                                    <a href="{{ route('attendances.edit', $attendance) }}"
                                       class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200">
                                        Edit
                                    </a>

                                    <form action="{{ route('attendances.destroy', $attendance) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data presensi ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8"
                                class="px-6 py-10 text-center text-gray-400">
                                Belum ada data presensi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection