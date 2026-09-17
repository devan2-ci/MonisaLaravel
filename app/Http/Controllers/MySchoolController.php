<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class MySchoolController extends Controller
{
    /**
     * Menampilkan sekolah milik admin yang sedang login.
     */
    public function index()
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        return view('admin.school.index', compact('school'));
    }

    /**
     * Form edit sekolah.
     */
    public function edit(string $id)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        // Pastikan admin hanya bisa mengedit sekolah miliknya
        if ((int) $school->id !== (int) $id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit sekolah ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | Provinsi
        |--------------------------------------------------------------------------
        */
        $provinces = Province::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Kota
        |--------------------------------------------------------------------------
        |
        | Jika sekolah sudah memiliki province_id,
        | ambil province tersebut lalu gunakan code-nya
        | untuk mencari kota.
        |
        */
        $cities = collect();

        if ($school->province_id) {

            $province = Province::find($school->province_id);

            if ($province) {
                $cities = City::where('province_code', $province->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kecamatan
        |--------------------------------------------------------------------------
        */
        $districts = collect();

        if ($school->city_id) {

            $city = City::find($school->city_id);

            if ($city) {
                $districts = District::where('city_code', $city->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Kelurahan / Desa
        |--------------------------------------------------------------------------
        */
        $villages = collect();

        if ($school->district_id) {

            $district = District::find($school->district_id);

            if ($district) {
                $villages = Village::where('district_code', $district->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('admin.school.edit', compact(
            'school',
            'provinces',
            'cities',
            'districts',
            'villages'
        ));
    }

    /**
     * Update data sekolah.
     */
    public function update(Request $request, string $id)
    {
        $admin = Auth::user()->admin;

        if (!$admin || !$admin->school) {
            abort(404, 'Sekolah belum terhubung dengan akun admin.');
        }

        $school = $admin->school;

        // Pastikan sekolah adalah milik admin
        if ((int) $school->id !== (int) $id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit sekolah ini.');
        }

        $validated = $request->validate([
            'kode_sekolah' => [
                'required',
                'string',
                'max:8',
                'unique:schools,kode_sekolah,' . $school->id,
            ],

            'nama' => 'required|string|max:255',

            'alamat_lengkap' => 'nullable|string',

            'province_id' => 'nullable|exists:indonesia_provinces,id',

            'city_id' => 'nullable|exists:indonesia_cities,id',

            'district_id' => 'nullable|exists:indonesia_districts,id',

            'village_id' => 'nullable|exists:indonesia_villages,id',

            'kode_pos' => 'nullable|string|max:10',

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'no_telepon' => 'nullable|string|max:20',

            'email' => 'nullable|email|max:255',

            'link_website' => 'nullable|string|max:255',

            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan relasi wilayah berurutan
        |--------------------------------------------------------------------------
        |
        | Misalnya user memilih Provinsi A tetapi mengirim city_id
        | milik Provinsi B. Kita kosongkan data anak yang tidak sesuai.
        |
        */

        if (!empty($validated['province_id'])) {

            $province = Province::find($validated['province_id']);

            if (!$province) {
                $validated['province_id'] = null;
            }

            // Jika provinsi berubah, periksa kota
            if (!empty($validated['city_id']) && $province) {

                $city = City::find($validated['city_id']);

                if (!$city || $city->province_code !== $province->code) {
                    $validated['city_id'] = null;
                    $validated['district_id'] = null;
                    $validated['village_id'] = null;
                }
            }
        } else {

            // Kalau provinsi dikosongkan,
            // semua wilayah di bawahnya ikut dikosongkan.
            $validated['city_id'] = null;
            $validated['district_id'] = null;
            $validated['village_id'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Periksa kecamatan
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['city_id']) && !empty($validated['district_id'])) {

            $city = City::find($validated['city_id']);
            $district = District::find($validated['district_id']);

            if (
                !$city ||
                !$district ||
                $district->city_code !== $city->code
            ) {
                $validated['district_id'] = null;
                $validated['village_id'] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Periksa kelurahan
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['district_id']) && !empty($validated['village_id'])) {

            $district = District::find($validated['district_id']);
            $village = Village::find($validated['village_id']);

            if (
                !$district ||
                !$village ||
                $village->district_code !== $district->code
            ) {
                $validated['village_id'] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Upload foto
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            // Hapus foto lama
            if (
                $school->photo &&
                Storage::disk('public')->exists($school->photo)
            ) {
                Storage::disk('public')->delete($school->photo);
            }

            // Simpan foto baru
            $validated['photo'] = $request
                ->file('photo')
                ->store('schools', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan perubahan
        |--------------------------------------------------------------------------
        */

        $school->update($validated);

        return redirect()
            ->route('my-school.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    /**
     * AJAX: mengambil kota berdasarkan provinsi.
     */
    public function cities($provinceId)
    {
        $province = Province::find($provinceId);

        if (!$province) {
            return response()->json([]);
        }

        $cities = City::where('province_code', $province->code)
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
            ]);

        return response()->json($cities);
    }

    /**
     * AJAX: mengambil kecamatan berdasarkan kota.
     */
    public function districts($cityId)
    {
        $city = City::find($cityId);

        if (!$city) {
            return response()->json([]);
        }

        $districts = District::where('city_code', $city->code)
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
            ]);

        return response()->json($districts);
    }

    /**
     * AJAX: mengambil kelurahan berdasarkan kecamatan.
     */
    public function villages($districtId)
    {
        $district = District::find($districtId);

        if (!$district) {
            return response()->json([]);
        }

        $villages = Village::where('district_code', $district->code)
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
            ]);

        return response()->json($villages);
    }
}