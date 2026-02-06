<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        $clientesNuevosMes = Cliente::whereMonth('created_at', now()->month)->count();
        return view('admin.index', compact('totalClientes','clientesNuevosMes'));
    }
}
