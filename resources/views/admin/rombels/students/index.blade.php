@extends('layout.app')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Siswa Kelas {{ $rombel->name }}
            </h1>

            <p class="mt-1 text-gray-500">
                {{ $rombel->schoolMajor->major->name ?? '-' }}
                • {{ $rombel->jenjang }}
                • Tahun Ajaran {{ $rombel->tahun_ajaran }}
            </p>
        </div>

        <div>

            <a
                href="{{ route('rombels.index') }}"
                class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
            >
                Kembali
            </a>

            <a
                href="{{ route('rombels.students.create', $rombel) }}"
                class="inline-flex items-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
            >
                Tambah Siswa
            </a>
            
        </div>

    </div>


    {{-- Alert --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl bg-green-50 px-5 py-4 text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Data siswa --}}
    @if($students->count() > 0)

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <table class="w-full">

                <thead class="border-b bg-gray-50">

                    <tr>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            NIS
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            NISN
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Nama
                        </th>

                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach($students as $index => $student)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $student->nis ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $student->nisn ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $student->name }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <form
                                    action="{{ route('rombels.students.destroy', [$rombel, $student]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Keluarkan siswa ini dari rombel?')"
                                    class="inline"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50"
                                    >
                                        Keluarkan
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

            <h2 class="text-lg font-semibold text-gray-800">
                Belum ada siswa
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Belum ada siswa yang dimasukkan ke rombel ini.
            </p>

            <a
                href="{{ route('rombels.students.create', $rombel) }}"
                class="mt-5 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700"
            >
                Tambah Siswa
            </a>

        </div>

    @endif

</div>

@endsection