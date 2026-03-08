<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
<<<<<<< HEAD
                            ->whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();
=======
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
>>>>>>> 1c124dea6aceb18f852a11411def43d02fd9729a

        $clientesConCuotasVencidas = Cliente::whereHas('prestamos.pagos', function ($query) {
            $query->where('estado', 'pendiente')
                  ->whereDate('fecha_vencimiento', '<', now()->toDateString());
        })->count();

        $cuotasVencidasTotal = Pago::where('estado', 'pendiente')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->count();

        $montoVencidoTotal = Pago::where('estado', 'pendiente')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->selectRaw('COALESCE(SUM(CASE WHEN monto_cuota > monto_total_pagado THEN monto_cuota - monto_total_pagado ELSE 0 END), 0) as total')
            ->value('total');

        $inicioRango = now()->copy()->subMonth(11)->startOfMonth();

        $capitalInteresPorMes = Pago::query()
            ->whereNotNull('fecha_cancelado')
            ->whereDate('fecha_cancelado', '>=', $inicioRango)
<<<<<<< HEAD
            ->selectRaw('YEAR(fecha_cancelado) as anio, MONTH(fecha_cancelado) as mes, COALESCE(SUM(monto_capital),0) as total_capital, COALESCE(SUM(monto_interes),0) as total_interes')
=======
            ->selectRaw('YEAR(fecha_cancelado) as anio, MONTH(fecha_cancelado) as mes, COALESCE(SUM(monto_capital), 0) as total_capital, COALESCE(SUM(monto_interes), 0) as total_interes')
>>>>>>> 1c124dea6aceb18f852a11411def43d02fd9729a
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->keyBy(fn($item) => $item->anio . '-' . str_pad((string) $item->mes, 2, '0', STR_PAD_LEFT));

        $labelsCapitalInteres = [];
        $datosCapitalMes = [];
        $datosInteresMes = [];

        for ($i = 11; $i >= 0; $i--) {
            $fecha = now()->copy()->subMonths($i);
            $clave = $fecha->format('Y-m');

            $labelsCapitalInteres[] = Carbon::createFromDate($fecha->year, $fecha->month, 1)->translatedFormat('M Y');
            $datosCapitalMes[] = (float) ($capitalInteresPorMes[$clave]->total_capital ?? 0);
            $datosInteresMes[] = (float) ($capitalInteresPorMes[$clave]->total_interes ?? 0);
        }

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
            'clientesConCuotasVencidas',
            'cuotasVencidasTotal',
            'montoVencidoTotal',
            'labelsCapitalInteres',
            'datosCapitalMes',
            'datosInteresMes',
<<<<<<< HEAD
        ));

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
=======
>>>>>>> 1c124dea6aceb18f852a11411def43d02fd9729a
        ));
    }
}
