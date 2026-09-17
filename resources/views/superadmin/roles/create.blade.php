@extends('layout.app')

@section('content')

<div class="space-y-6, p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Role
        </h1>
        <p class="text-gray-500">
            Tambahkan role baru ke dalam sistem.
        </p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-gray-100">

        <form action="{{ route('roles.store') }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 gap-6 p-8">

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Role
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500">

                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Guard Name
                    </label>

                    <select
                        name="guard_name"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500">

                        <option value="web">Web</option>
                        <option value="api">API</option>

                    </select>

                    @error('guard_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 px-8 py-5">

                <a href="{{ route('roles.index') }}"
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