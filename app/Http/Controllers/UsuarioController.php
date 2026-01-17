<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'SUPER ADMINISTRADOR')->get();
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
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|in:DNI,Pasaporte,Carnet de Extrangeria,RUC,Carnet de Identidad',
            'numero_documento' => 'required|string|unique:users,numero_documento',
            'celular' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_relacion' => 'required|string|max:100',
            'estado' => 'required|in:Activo,Inactivo',
            'foto_perfil' => 'nullable|image|max:2048',
        ]);

        $usuario = new User();
        $usuario->name = $request->nombres . ' ' . $request->apellidos;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->tipo_documento = $request->tipo_documento;
        $usuario->numero_documento = $request->numero_documento;
        $usuario->celular = $request->celular;
        $usuario->direccion = $request->direccion;
        $usuario->fecha_nacimiento = $request->fecha_nacimiento;
        $usuario->genero = $request->genero;
        $usuario->contacto_nombre = $request->contacto_nombre;
        $usuario->contacto_telefono = $request->contacto_telefono;
        $usuario->contacto_relacion = $request->contacto_relacion;
        $usuario->estado = $request->estado;
        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $usuario->foto_perfil = $path;
        }
        $usuario->save();
        $usuario->assignRole($request->rol);

        return redirect()->route('admin.usuarios.index')
            ->with('mensaje', 'Usuario creado correctamente.')
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
