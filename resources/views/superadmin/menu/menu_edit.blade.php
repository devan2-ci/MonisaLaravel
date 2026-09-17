@extends('layout.app')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Menu
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Edit menu yang sudah ada beserta role yang dapat mengaksesnya.
            </p>
        </div>

        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @method('PUT')

            @csrf

            <div class="p-6 space-y-6">

                {{-- Nama Menu --}}
                <div>
                    <label class="block font-medium mb-2">
                        Nama Menu
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $menu->name) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring focus:ring-blue-200"
                        placeholder="Contoh : Dashboard">

                    @error('name')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Route --}}
                <div>
                    <label class="block font-medium mb-2">
                        Route
                    </label>

                    <input
                        type="text"
                        name="route"
                        value="{{ old('route', $menu->route) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2"
                        placeholder="dashboard.index">
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block font-medium mb-2">
                        Icon
                    </label>

                    <input
                        type="text"
                        name="icon"
                        value="{{ old('icon', $menu->icon) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2"
                        placeholder="fa-solid fa-house">

                    <small class="text-gray-500">
                        Gunakan class icon Font Awesome.
                    </small>
                </div>

                {{-- Parent --}}
                <div>
                    <label class="block font-medium mb-2">
                        Parent Menu
                    </label>

                    <select
                        name="parent_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2">

                        <option value="">-- Tidak Ada Parent --</option>

                        @foreach($parents as $parent)
                            <option
                                value="{{ $parent->id }}"
                                {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Urutan --}}
                <div>
                    <label class="block font-medium mb-2">
                        Urutan
                    </label>

                    <input
                        type="number"
                        name="order"
                        value="{{ old('order', $menu->order) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2">
                </div>

                {{-- Status --}}
                <div>

                    <label class="block font-medium mb-2">
                        Status
                    </label>

                    <select
                        name="is_active"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2">

                        <option value="1" {{ old('is_active', $menu->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $menu->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>

                    </select>

                </div>

                {{-- Role --}}
                <div>

                    <label class="block font-medium mb-3">
                        Hak Akses Role
                    </label>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                        @foreach($roles as $role)

                            <label class="flex items-center gap-2 border rounded-lg p-3 cursor-pointer hover:bg-gray-50">

                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->id }}"
                                    class="rounded"
                                    {{ in_array($role->id, old('roles', $menu->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                >

                                <span>{{ $role->name }}</span>

                            </label>

                        @endforeach

                    </div>

                </div>

            </div>

            <div class="border-t px-6 py-4 flex justify-end gap-3">

                <a href="{{ route('menus.index') }}"
                    class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection