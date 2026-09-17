@extends('layout.app')

@section('content')

<div class="p-6 max-w-3xl">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Presensi
        </h1>

        <p class="text-gray-500">
            Perbarui data kehadiran siswa.
        </p>

    </div>


    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-100 px-5 py-4 text-red-700">

            <ul class="list-disc list-inside">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form action="{{ route('attendances.update', $attendance) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border p-6">

        @csrf
        @method('PUT')


        {{-- Siswa --}}
        <div class="mb-5">

            <label class="block mb-2 font-medium text-gray-700">
                Siswa
            </label>

            <select name="student_id"
                    class="w-full rounded-xl border-gray-300"
                    required>

                @foreach($students as $student)

                    <option value="{{ $student->id }}"
                        {{ old('student_id', $attendance->student_id) == $student->id ? 'selected' : '' }}>

                        {{ $student->name }}

                        @if($student->rombel)
                            - {{ $student->rombel->jenjang }}
                            {{ $student->rombel->schoolMajor->major->name ?? '' }}
                            {{ $student->rombel->name }}
                        @endif

                    </option>

                @endforeach

            </select>

        </div>


        {{-- Tanggal --}}
        <div class="mb-5">

            <label class="block mb-2 font-medium text-gray-700">
                Tanggal
            </label>

            <input type="date"
                   name="tanggal"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($attendance->tanggal)->format('Y-m-d')) }}"
                   class="w-full rounded-xl border-gray-300"
                   required>

        </div>


        {{-- Jam --}}
        <div class="mb-5">

            <label class="block mb-2 font-medium text-gray-700">
                Jam
            </label>

            <input type="time"
                   name="jam"
                   value="{{ old('jam', \Carbon\Carbon::parse($attendance->jam)->format('H:i')) }}"
                   class="w-full rounded-xl border-gray-300"
                   required>

        </div>


        {{-- Status --}}
        <div class="mb-5">

            <label class="block mb-2 font-medium text-gray-700">
                Status
            </label>

            <select name="status"
                    class="w-full rounded-xl border-gray-300"
                    required>

                @foreach(['hadir', 'izin', 'sakit', 'alpha'] as $status)

                    <option value="{{ $status }}"
                        {{ old('status', $attendance->status) == $status ? 'selected' : '' }}>

                        {{ ucfirst($status) }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- Lampiran lama --}}
        @if($attendance->lampiran)

            <div class="mb-5">

                <p class="mb-2 font-medium text-gray-700">
                    Lampiran Saat Ini
                </p>

                <a href="{{ asset('storage/' . $attendance->lampiran) }}"
                   target="_blank"
                   class="text-blue-600 hover:underline">

                    Lihat lampiran

                </a>

            </div>

        @endif


        {{-- Lampiran baru --}}
        <div class="mb-5">

            <label class="block mb-2 font-medium text-gray-700">
                Ganti Lampiran
            </label>

            <input type="file"
                   name="lampiran"
                   accept=".jpg,.jpeg,.png"
                   class="w-full rounded-xl border-gray-300">

            <p class="mt-1 text-xs text-gray-400">
                Kosongkan jika tidak ingin mengganti lampiran.
            </p>

        </div>


        {{-- Keterangan --}}
        <div class="mb-6">

            <label class="block mb-2 font-medium text-gray-700">
                Keterangan
            </label>

            <textarea name="keterangan"
                      rows="4"
                      class="w-full rounded-xl border-gray-300">{{ old('keterangan', $attendance->keterangan) }}</textarea>

        </div>


        <div class="flex justify-end gap-3">

            <a href="{{ route('attendances.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700">

                Batal

            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700">

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@endsection