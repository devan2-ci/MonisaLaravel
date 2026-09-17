<?php

namespace App\Http\Controllers;

use App\Models\Schools;
use App\Models\SchoolType;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class SchoolsController extends Controller
{
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = Schools::all();
        return view('superadmin.school.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();

        $schoolTypes = SchoolType::orderBy('id')->get();

        return view('superadmin.school.create', [
            'provinces' => $provinces,
            'schoolTypes' => $schoolTypes,
            'cities' => collect(),
            'districts' => collect(),
            'villages' => collect(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_sekolah' => 'required|string|max:8|unique:schools,kode_sekolah',
            'nama' => 'required|string|max:255',
            'school_type_id' => 'required|exists:school_types,id',
            'alamat_lengkap' => 'nullable|string',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

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

        Schools::create($validated);

        return redirect()->route('schools.index')->with('success', 'School created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schools $schools)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schools $school)
    {
        $provinces = Province::orderBy('name')->get();

        $schoolTypes = SchoolType::orderBy('id')->get();

        $cities = collect();

        if ($school->province_id) {

            $province = Province::find($school->province_id);

            if ($province) {
                $cities = City::where('province_code', $province->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        $districts = collect();

        if ($school->city_id) {

            $city = City::find($school->city_id);

            if ($city) {
                $districts = District::where('city_code', $city->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        $villages = collect();

        if ($school->district_id) {

            $district = District::find($school->district_id);

            if ($district) {
                $villages = Village::where('district_code', $district->code)
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('superadmin.school.edit', compact('school', 'schoolTypes', 'provinces','cities','districts','villages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schools $school)
    {
        $validated = $request->validate([
            // 'kode_sekolah' => 'required|string|max:8|unique:schools,kode_sekolah',
            'nama' => 'required|string|max:255',
            'school_type_id' => 'required|exists:school_types,id',
            'alamat_lengkap' => 'nullable|string',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'village_id' => 'nullable|exists:indonesia_villages,id',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

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

        $school->update($validated);

        return redirect()->route('schools.index')->with('success', 'School edited successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schools $school)
    {
        $school->delete();

        return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
    }
}
