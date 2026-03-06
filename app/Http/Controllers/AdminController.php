<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $ajuste = Ajuste::first();

        $total_roles = Role::count();
        $rolesNuevosMes = Role::whereMonth('created_at', now()->month)->count();

        $totalUsuarios = User::count();
        $usuariosNuevosMes = User::whereMonth('created_at', now()->month)->count();

        $totalClientes = Cliente::count();
        $clientesNuevosMes = Cliente::whereMonth('created_at', now()->month)->count();

        $totalCategorias = Categoria::count();
        $categoriasNuevasMes = Categoria::whereMonth('created_at', now()->month)->count();

        $montoPrestadoTotal = Prestamo::sum('monto_prestado');
        $capitalRecuperadoTotal = Pago::whereNotNull('fecha_cancelado')->sum('monto_capital');
        $saldoPendienteTotal = Pago::where('estado', 'pendiente')
            ->selectRaw('COALESCE(SUM(CASE WHEN monto_cuota > monto_total_pagado THEN monto_cuota - monto_total_pagado ELSE 0 END), 0) as total')
            ->value('total');
        $carteraActivaTotal = $saldoPendienteTotal;

        $totalPrestamos = Prestamo::count();
        $prestamosNuevosMes = Prestamo::whereMonth('created_at', now()->month)->count();

        $totalPrestamosActivos = Prestamo::where('estado', 'pendiente')->count();
        $prestamosActivosMes = Prestamo::where('estado', 'pendiente')
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();

        return view('admin.index', compact(
            'ajuste',
            'totalClientes',
            'clientesNuevosMes',
            'total_roles',
            'rolesNuevosMes',
            'totalUsuarios',
            'usuariosNuevosMes',
            'montoPrestadoTotal',
            'capitalRecuperadoTotal',
            'saldoPendienteTotal',
            'carteraActivaTotal',
            'totalCategorias',
            'categoriasNuevasMes',
            'totalPrestamos',
            'prestamosNuevosMes',
            'totalPrestamosActivos',
            'prestamosActivosMes',
        ));
    }
}
