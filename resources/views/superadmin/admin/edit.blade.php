@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Admin
        </h1>

        <p class="text-gray-500">
            Edit informasi admin sekolah.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('admins.update', $admin->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

                {{-- Nama --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $admin->name) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $admin->user->email) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                {{-- Username --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        value="{{ old('username', $admin->user->username) }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">
                </div>

                {{-- Sekolah --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Sekolah
                    </label>

                    <select
                        name="school_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3">

                        <option value="{{ old('school_id', $admin->school_id) }}">Pilih Sekolah</option>

                        @foreach($schools as $school)
                            <option
                                value="{{ $school->id }}"
                                {{ old('school_id', $admin->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->nama }}
                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a
                    href="{{ route('admins.index') }}"
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