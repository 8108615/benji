<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $total_roles = Role::count();
        $rolesNuevosMes = Role::whereMonth('created_at', now()->month)->count();

        $totalUsuarios = User::count();
        $usuariosNuevosMes = User::whereMonth('created_at', now()->month)->count();

        $totalClientes = Cliente::count();
        $clientesNuevosMes = Cliente::whereMonth('created_at', now()->month)->count();
        return view('admin.index', compact('totalClientes', 'clientesNuevosMes', 'total_roles', 'rolesNuevosMes', 'totalUsuarios', 'usuariosNuevosMes'));
    }
}
