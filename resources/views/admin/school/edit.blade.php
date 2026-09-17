@extends('layout.app')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Profil Sekolah
        </h1>

        <p class="text-gray-500 mt-1">
            Perbarui informasi sekolah Anda.
        </p>
    </div>

    <form
        action="{{ route('my-school.update', $school->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- Informasi Utama --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

                <div class="border-b border-gray-100 px-8 py-5">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Informasi Sekolah
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Informasi dasar mengenai sekolah.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

                    {{-- Kode Sekolah --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Kode Sekolah
                        </label>

                        <input
                            type="text"
                            name="kode_sekolah"
                            value="{{ old('kode_sekolah', $school->kode_sekolah) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('kode_sekolah')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nama Sekolah --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Nama Sekolah
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama', $school->nama) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            name="no_telepon"
                            value="{{ old('no_telepon', $school->no_telepon) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >

                        @error('no_telepon')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Email Sekolah
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $school->email) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Website --}}
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Website
                        </label>

                        <input
                            type="text"
                            name="link_website"
                            value="{{ old('link_website', $school->link_website) }}"
                            placeholder="https://contoh.sch.id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >

                        @error('link_website')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- Alamat --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

                <div class="border-b border-gray-100 px-8 py-5">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Alamat Sekolah
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

                    {{-- Alamat Lengkap --}}
                    <div class="md:col-span-2">

                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Alamat Lengkap
                        </label>

                        <textarea
                            name="alamat_lengkap"
                            rows="4"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >{{ old('alamat_lengkap', $school->alamat_lengkap) }}</textarea>

                        @error('alamat_lengkap')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Provinsi --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Provinsi
                        </label>

                        <select
                            name="province_id"
                            id="province_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >
                            <option value="">
                                Pilih Provinsi
                            </option>

                            @foreach ($provinces as $item)
                                <option 
                                    value="{{ $item->id }}"
                                    {{ old('province_id', $school->province_id) == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('province_id')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Kota --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Kota / Kabupaten
                        </label>

                        <select
                            name="city_id"
                            id="city_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            {{ $school->province_id ? '' : 'disabled' }}
                        >
                            <option value="">
                                Pilih Kota / Kabupaten
                            </option>

                            @foreach ($cities as $city)
                                <option 
                                    value="{{ $city->id }}"
                                    {{ old('city_id', $school->city_id) == $city->id ? 'selected' : '' }}
                                >
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('city_id')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Kecamatan --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Kecamatan
                        </label>

                        <select
                            name="district_id"
                            id="district_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            {{ $school->city_id ? '' : 'disabled' }}
                        >
                            <option value="">
                                Pilih Kecamatan
                            </option>

                            @foreach ($districts as $district)
                                <option 
                                    value="{{ $district->id }}"
                                    {{ old('district_id', $school->district_id) == $district->id ? 'selected' : '' }}
                                >
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Kelurahan --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Kelurahan / Desa
                        </label>

                        <select
                            name="village_id"
                            id="village_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            {{ $school->district_id ? '' : 'disabled' }}
                        >
                            <option value="">
                                Pilih Kelurahan / Desa
                            </option>
                            @foreach ($villages as $village)
                                <option 
                                    value="{{ $village->id }}"
                                    {{ old('village_id', $school->village_id) == $village->id ? 'selected' : '' }}
                                >
                                    {{ $village->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('village_id')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Kode Pos --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Kode Pos
                        </label>

                        <input
                            type="text"
                            name="kode_pos"
                            value="{{ old('kode_pos', $school->kode_pos) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >
                        @error('kode_pos')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>


            {{-- Lokasi --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

                <div class="border-b border-gray-100 px-8 py-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Lokasi
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Koordinat lokasi sekolah.
                    </p>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">

                    {{-- Latitude --}}
                    <div>

                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            value="{{ old('latitude', $school->latitude) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >

                    </div>


                    {{-- Longitude --}}
                    <div>

                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            value="{{ old('longitude', $school->longitude) }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3"
                        >

                    </div>

                </div>
            </div>


            {{-- Foto --}}
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

                <div class="border-b border-gray-100 px-8 py-5">

                    <h2 class="text-lg font-semibold text-gray-800">
                        Foto Sekolah
                    </h2>

                </div>

                <div class="p-8">

                    @if($school->photo)

                        <div class="mb-4">

                            <p class="text-sm text-gray-500 mb-2">
                                Foto saat ini
                            </p>

                            <img
                                src="{{ asset('storage/' . $school->photo) }}"
                                alt="{{ $school->nama }}"
                                class="w-48 h-32 object-cover rounded-xl border"
                            >

                        </div>

                    @endif

                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Ganti Foto
                    </label>

                    <input
                        type="file"
                        name="photo"
                        accept="image/*"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    >

                    <p class="text-xs text-gray-500 mt-2">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    @error('photo')
                        <p class="mt-1 text-sm text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>
            </div>


            {{-- Tombol --}}
            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('my-school.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-gray-700 hover:bg-gray-100"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-white hover:bg-indigo-700"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        const districtSelect = document.getElementById('district_id');
        const villageSelect = document.getElementById('village_id');


        // =====================================================
        // PROVINSI -> KOTA
        // =====================================================

        provinceSelect.addEventListener('change', function () {

            const provinceId = this.value;

            citySelect.innerHTML =
                '<option value="">Memuat kota...</option>';

            districtSelect.innerHTML =
                '<option value="">Pilih Kecamatan</option>';

            villageSelect.innerHTML =
                '<option value="">Pilih Kelurahan / Desa</option>';

            citySelect.disabled = true;
            districtSelect.disabled = true;
            villageSelect.disabled = true;


            if (!provinceId) {

                citySelect.innerHTML =
                    '<option value="">Pilih Kota / Kabupaten</option>';

                return;
            }


            fetch(`{{ url('my-school/cities') }}/${provinceId}`)
                .then(response => {

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data kota');
                    }

                    return response.json();
                })
                .then(cities => {

                    citySelect.innerHTML =
                        '<option value="">Pilih Kota / Kabupaten</option>';

                    cities.forEach(city => {

                        const option = document.createElement('option');

                        option.value = city.id;
                        option.textContent = city.name;

                        citySelect.appendChild(option);
                    });

                    citySelect.disabled = false;
                })
                .catch(error => {

                    console.error(error);

                    citySelect.innerHTML =
                        '<option value="">Gagal memuat kota</option>';
                });
        });


        // =====================================================
        // KOTA -> KECAMATAN
        // =====================================================

        citySelect.addEventListener('change', function () {

            const cityId = this.value;

            districtSelect.innerHTML =
                '<option value="">Memuat kecamatan...</option>';

            villageSelect.innerHTML =
                '<option value="">Pilih Kelurahan / Desa</option>';

            districtSelect.disabled = true;
            villageSelect.disabled = true;


            if (!cityId) {

                districtSelect.innerHTML =
                    '<option value="">Pilih Kecamatan</option>';

                return;
            }


            fetch(`{{ url('my-school/districts') }}/${cityId}`)
                .then(response => {

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data kecamatan');
                    }

                    return response.json();
                })
                .then(districts => {

                    districtSelect.innerHTML =
                        '<option value="">Pilih Kecamatan</option>';

                    districts.forEach(district => {

                        const option = document.createElement('option');

                        option.value = district.id;
                        option.textContent = district.name;

                        districtSelect.appendChild(option);
                    });

                    districtSelect.disabled = false;
                })
                .catch(error => {

                    console.error(error);

                    districtSelect.innerHTML =
                        '<option value="">Gagal memuat kecamatan</option>';
                });
        });


        // =====================================================
        // KECAMATAN -> KELURAHAN
        // =====================================================

        districtSelect.addEventListener('change', function () {

            const districtId = this.value;

            villageSelect.innerHTML =
                '<option value="">Memuat kelurahan...</option>';

            villageSelect.disabled = true;


            if (!districtId) {

                villageSelect.innerHTML =
                    '<option value="">Pilih Kelurahan / Desa</option>';

                return;
            }


            fetch(`{{ url('my-school/villages') }}/${districtId}`)
                .then(response => {

                    if (!response.ok) {
                        throw new Error('Gagal mengambil data kelurahan');
                    }

                    return response.json();
                })
                .then(villages => {

                    villageSelect.innerHTML =
                        '<option value="">Pilih Kelurahan / Desa</option>';

                    villages.forEach(village => {

                        const option = document.createElement('option');

                        option.value = village.id;
                        option.textContent = village.name;

                        villageSelect.appendChild(option);
                    });

                    villageSelect.disabled = false;
                })
                .catch(error => {

                    console.error(error);

                    villageSelect.innerHTML =
                        '<option value="">Gagal memuat kelurahan</option>';
                });
        });

    });
</script>
@endsection

