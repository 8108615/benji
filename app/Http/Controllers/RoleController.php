<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')->paginate(10);
        //return response()->json($roles);
        return view('admin.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request->all());
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol Guardado correctamente.')
                ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rol = Role::find($id);
        //return response()->json($rol);
        return view('admin.roles.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //return response()->json($request->all());
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$id,
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol Modificado correctamente.')
                ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::find($id);
        $rol->delete();
        return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol Eliminado correctamente.')
                ->with('icono', 'success');
    }
}
