<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $usuario = User::withTrashed();

        if ($buscar) {
            $usuario->where(function ($query) use ($buscar) {
                $query->where('name', 'like', '%' . $buscar . '%')
                    ->orWhere('email', 'like', '%' . $buscar . '%');
            });
        }

        $usuarios = $usuario->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')
            ->where('name', '!=', 'CLIENTE')
            ->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request->all());
        $request->validate([
            'rol' => 'required|string|exists:roles,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'name' => 'required|string|max:255',
        ]);

        $usuario = new User();
        $usuario->name = $request->nombres . ' ' . $request->apellidos;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        
        $usuario->save();

        $usuario->assignRole($request->rol);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado exitosamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')->get();
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //return response()->json($request->all());
        $request->validate([
            'rol' => 'required|string|exists:roles,name',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|confirmed|min:8',
            'name' => 'required|string|max:255',
            
        ]);
        $usuario = User::findOrFail($id);
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->save();
        $usuario->syncRoles($request->rol);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario Actualizado Correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //echo "Eliminar usuario con ID: " . $id;
        $usuario = User::findOrFail($id);
        $usuario->estado = 'Inactivo';
        $usuario->save();

        $usuario->delete();

        $cliente = Cliente::where('user_id', $usuario->id)->first();
        if ($cliente) {
            $cliente->delete();
        }

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario eliminado correctamente')
            ->with('icono', 'success');
    }

    public function restaurar($id)
    {
        //echo "Restaurar usuario con ID: " . $id;
        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->restore();
        $usuario->estado = 'Activo';
        $usuario->save();

        $cliente = Cliente::withTrashed()->where('user_id', $usuario->id)->first();
        if ($cliente && $cliente->trashed()) {
            $cliente->restore();
        }

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario restaurado correctamente')
            ->with('icono', 'success');
    }
}
