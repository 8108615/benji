<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $cliente = Cliente::with('user')->withTrashed();

        if ($buscar) {
            $cliente->where(function ($query) use ($buscar) {
                $query->where('nombres', 'like', '%' . $buscar . '%')
                      ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                      ->orWhere('numero_documento', 'like', '%' . $buscar . '%')
                      ->orWhere('celular', 'like', '%' . $buscar . '%');
            });
        }

        $clientes = $cliente->paginate(10);
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request->all());

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|in:DNI,Pasaporte,Carnet de Extranjería,RUC,Carnet de identidad',
            'numero_documento' => 'required|string|unique:clientes,numero_documento',
            'celular' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_relacion' => 'required|string|max:255',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $usuario = new User();
        $usuario->name = $request->nombres . ' ' . $request->apellidos;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->estado = $request->estado;
        $usuario->save();

        $usuario->assignRole('CLIENTE');

        $cliente = new Cliente();
        $cliente->user_id = $usuario->id;
        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->tipo_documento = $request->tipo_documento;
        $cliente->numero_documento = $request->numero_documento;
        $cliente->celular = $request->celular;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = $request->fecha_nacimiento;
        $cliente->genero = $request->genero;
        $cliente->contacto_nombre = $request->contacto_nombre;
        $cliente->contacto_telefono = $request->contacto_telefono;
        $cliente->contacto_relacion = $request->contacto_relacion;
        if ($request->hasFile('foto_perfil')) {
            // If there is an existing photo, delete it from storage
            if ($cliente->foto_perfil && Storage::disk('public')->exists($cliente->foto_perfil)) {
                Storage::disk('public')->delete($cliente->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $cliente->foto_perfil = $path;
        }
        $cliente->save();

        return redirect()->route('admin.clientes.index')
        ->with('mensaje', 'Cliente creado exitosamente')
        ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //echo "Editar cliente ID: " . $id;
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //return response()->json($request->all());
        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->user_id);

        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|in:DNI,Pasaporte,Carnet de Extranjería,RUC,Carnet de identidad',
            'numero_documento' => 'required|string|unique:clientes,numero_documento,' . $id,
            'celular' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_relacion' => 'required|string|max:255',
            'estado' => 'required|in:Activo,Inactivo',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);




        $usuario->name = $request->nombres . ' ' . $request->apellidos;
        $usuario->email = $request->email;
        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->password);
        }
        $usuario->estado = $request->estado;
        $usuario->save();

        $cliente->nombres = $request->nombres;
        $cliente->apellidos = $request->apellidos;
        $cliente->tipo_documento = $request->tipo_documento;
        $cliente->numero_documento = $request->numero_documento;
        $cliente->celular = $request->celular;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = $request->fecha_nacimiento;
        $cliente->genero = $request->genero;
        $cliente->contacto_nombre = $request->contacto_nombre;
        $cliente->contacto_telefono = $request->contacto_telefono;
        $cliente->contacto_relacion = $request->contacto_relacion;
        if ($request->hasFile('foto_perfil')) {
            // si existe foto previa, eliminarla
            if ($cliente->foto_perfil && Storage::disk('public')->exists($cliente->foto_perfil)) {
                Storage::disk('public')->delete($cliente->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $cliente->foto_perfil = $path;
        }
        $cliente->save();

        return redirect()->route('admin.clientes.index')
        ->with('mensaje', 'Cliente actualizado exitosamente')
        ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //echo "Eliminar cliente con ID: " . $id;

        $cliente = Cliente::findOrFail($id);
        $usuario = User::findOrFail($cliente->user_id);

        $usuario->estado = 'Inactivo';
        $usuario->save();
        $usuario->delete();

        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente eliminado correctamente')
            ->with('icono', 'success');
    }

    public function restaurar($id)
    {
        //echo "Restaurar cliente con ID: " . $id;
        $cliente = Cliente::withTrashed()->findOrFail($id);
        $usuario = User::withTrashed()->findOrFail($cliente->user_id);

        $usuario->restore();
        $usuario->estado = 'Activo';
        $usuario->save();

        $cliente->restore();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente restaurado correctamente')
            ->with('icono', 'success');
    }
}
