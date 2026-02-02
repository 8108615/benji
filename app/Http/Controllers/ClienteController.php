<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $cliente = Cliente::query();

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
            'password'=> 'required|string|confirmed|min:8',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'tipo_documento' => 'required|in:DNI,Pasaporte,Carnet de Extrangeria,RUC,Carnet de Identidad',
            'numero_documento' => 'required|string|unique:clientes,numero_documento',
            'celular' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,|max:2048',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_telefono' => 'required|string|max:20',
            'contacto_relacion' => 'required|string|max:100',
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
        $cliente->save();

        return redirect()->route('admin.clientes.index')
                ->with('mensaje', 'Cliente Registrado correctamente.')
                ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
