@extends('layout.app')

@section('content')

<div class="space-y-6 p-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Sekolah
        </h1>

        <p class="text-gray-500">
            Tambahkan data sekolah baru ke dalam sistem.
        </p>
    </div>

    <div class="rounded-2xl bg-white border border-gray-100 shadow-sm">

        <form action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="p-8 space-y-8">

                <div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                        {{-- Kode sekolah --}}
                        <div>
                            <label class="block mb-2 font-medium">
                                Kode Sekolah
                            </label>

                            <input
                                type="text"
                                name="kode_sekolah"
                                value="{{ old('kode_sekolah') }}"
                                maxlength="8"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3">

                            @error('kode_sekolah')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Sekolah --}}
                        <div>
                            <label class="block mb-2 font-medium">
                                Tipe Sekolah
                            </label>

                            <select
                                name="school_type_id"
                                id="school_type_id"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3"
                                required
                            >
                                <option value="">
                                    Pilih Tipe Sekolah
                                </option>

                                @foreach ($schoolTypes as $type)
                                    <option
                                        value="{{ $type->id }}"
                                        {{ old('school_type_id') == $type->id ? 'selected' : '' }}
                                    >
                                        {{ $type->kode }}
                                    </option>
                                @endforeach
                            </select>

                            @error('school_type_id')
                                <p class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Nama Sekolah --}}
                        <div>
                            <label class="block mb-2 font-medium">
                                Nama Sekolah
                            </label>

                            <input
                                type="text"
                                name="nama"
                                value="{{ old('nama') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3">

                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="md:col-span-2">

                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Alamat Lengkap
                            </label>

                            <textarea
                                name="alamat_lengkap"
                                rows="4"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            ></textarea>

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
                            >
                                <option value="">
                                    Pilih Kota / Kabupaten
                                </option>

                                @foreach ($cities as $city)
                                    <option 
                                        value="{{ $city->id }}"
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
                            >
                                <option value="">
                                    Pilih Kecamatan
                                </option>

                                @foreach ($districts as $district)
                                    <option 
                                        value="{{ $district->id }}"
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
                            >
                                <option value="">
                                    Pilih Kelurahan / Desa
                                </option>
                                @foreach ($villages as $village)
                                    <option 
                                        value="{{ $village->id }}"
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
                                value=""
                                class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            >
                            @error('kode_pos')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Latitude --}}
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Latitude
                            </label>

                            <input
                                type="text"
                                name="latitude"
                                value=""
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
                                value=""
                                class="w-full rounded-xl border border-gray-300 px-4 py-3"
                            >

                        </div>

                    </div>

                </div>

            </div>

            <div class="border-t border-gray-100 px-8 py-5 flex justify-end gap-3">

                <a
                    href="{{ route('schools.index') }}"
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


            fetch(`{{ url('school/cities') }}/${provinceId}`)
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


            fetch(`{{ url('school/districts') }}/${cityId}`)
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


            fetch(`{{ url('school/villages') }}/${districtId}`)
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