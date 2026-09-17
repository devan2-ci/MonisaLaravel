<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        return view('superadmin.menu.menu_index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        $parents = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('superadmin.menu.menu_create', compact('roles', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'required|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'route' => $validated['route'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'order' => $validated['order'] ?? 0,
            'parent_id' => $validated['parent_id'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        $menu->roles()->sync($request->roles ?? []);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $roles = Role::all();
        $parents = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('superadmin.menu.menu_edit', compact('menu', 'roles', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'required|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'name' => $validate['name'],
            'route' => $validate['route'] ?? null,
            'icon' => $validate['icon'] ?? null,
            'order' => $validate['order'] ?? 0,
            'parent_id' => $validate['parent_id'] ?? null,
            'is_active' => $validate['is_active'],
        ]);
        $menu->roles()->sync($request->roles ?? []);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->roles()->detach();
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
