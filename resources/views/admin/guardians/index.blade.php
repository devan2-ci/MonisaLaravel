@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Guardian Management
            </h1>

            <p class="text-sm text-gray-500">
                Kelola data wali siswa
            </p>
        </div>

        <a href="{{ route('guardians.create') }}"
            class="mt-4 md:mt-0 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700">

            <span>+</span>
            Tambah Wali

        </a>

    </div>


    {{-- Success Alert --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">

            {{ session('success') }}

        </div>

    @endif


    {{-- Error Alert --}}
    @if(session('error'))

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

            {{ session('error') }}

        </div>

    @endif


    {{-- Validation Error --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

            <div class="font-semibold mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc pl-5 space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Card --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">


        {{-- Search --}}
        <div class="border-b border-gray-100 p-5">

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                {{-- Search --}}
                <div class="relative w-full md:w-80">

                    <input
                        type="text"
                        id="searchGuardian"
                        placeholder="Cari nama wali atau siswa..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-indigo-500 focus:bg-white focus:outline-none">

                </div>


                {{-- Total --}}
                <div class="text-sm text-gray-500">

                    Total Wali:

                    <span class="font-semibold text-gray-700">
                        {{ $guardians->total() }}
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
                            Nama Wali
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Hubungan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Siswa
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            No. HP
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Email
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody id="guardianTable">

                    @forelse($guardians as $index => $guardian)

                        <tr class="guardian-row border-t border-gray-100 transition duration-200 hover:bg-indigo-50/40">


                            {{-- No --}}
                            <td class="px-6 py-5 text-sm text-gray-600">

                                {{ $guardians->firstItem() + $index }}

                            </td>


                            {{-- Nama Wali --}}
                            <td class="px-6 py-5">

                                <div class="font-semibold text-gray-800">

                                    {{ $guardian->name }}

                                </div>

                            </td>


                            {{-- Hubungan --}}
                            <td class="px-6 py-5">

                                @if($guardian->relationship === 'ayah')

                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">

                                        Ayah

                                    </span>

                                @elseif($guardian->relationship === 'ibu')

                                    <span class="inline-flex items-center rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700">

                                        Ibu

                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">

                                        Wali

                                    </span>

                                @endif

                            </td>


                            {{-- Siswa --}}
                            <td class="px-6 py-5">

                                @if($guardian->student)

                                    <div class="font-medium text-gray-800">

                                        {{ $guardian->student->name }}

                                    </div>

                                    @if($guardian->student->nis)

                                        <div class="mt-1 text-xs text-gray-400">

                                            NIS: {{ $guardian->student->nis }}

                                        </div>

                                    @endif

                                @else

                                    <span class="text-sm text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- No HP --}}
                            <td class="px-6 py-5">

                                @if($guardian->phone)

                                    <span class="text-sm text-gray-600">

                                        {{ $guardian->phone }}

                                    </span>

                                @else

                                    <span class="text-sm text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Email --}}
                            <td class="px-6 py-5">

                                @if($guardian->email)

                                    <span class="text-sm text-gray-600">

                                        {{ $guardian->email }}

                                    </span>

                                @else

                                    <span class="text-sm text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi --}}
                            <td class="px-6 py-5">

                                <div class="flex justify-center gap-2">


                                    {{-- Edit --}}
                                    <a href="{{ route('guardians.edit', $guardian->id) }}"
                                        class="rounded-lg bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">

                                        Edit

                                    </a>


                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('guardians.destroy', $guardian->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Yakin ingin menghapus wali {{ $guardian->name }}?')"
                                            class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7" class="py-16 text-center">

                                <div class="space-y-2">

                                    <div class="text-lg font-medium text-gray-500">

                                        Tidak ada data

                                    </div>

                                    <div class="text-sm text-gray-400">

                                        Belum ada data wali siswa yang dibuat.

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($guardians->hasPages())

            <div class="border-t border-gray-100 px-6 py-4">

                {{ $guardians->links() }}

            </div>

        @endif

    </div>

</div>


{{-- Search Script --}}
<script>

    document
        .getElementById('searchGuardian')
        .addEventListener('keyup', function () {

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll('.guardian-row');

            rows.forEach(function (row) {

                let text = row.textContent.toLowerCase();

                if (text.includes(keyword)) {

                    row.style.display = '';

                } else {

                    row.style.display = 'none';

                }

            });

        });

</script>

@endsection