<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Cliente;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar'));
        $ajuste = Ajuste::first();
        $hoy = now()->toDateString();

        $filtroPagoVencidos = function ($query) use ($hoy) {
            $query->where('estado', 'pendiente')
                ->whereDate('fecha_vencimiento', '<', $hoy);
        };

        $clientesQuery = Cliente::query()
            ->whereHas('prestamos.pagos', $filtroPagoVencidos)
            ->with([
                'prestamos.pagos' => function ($query) use ($filtroPagoVencidos) {
                    $filtroPagoVencidos($query);
                    $query->orderBy('fecha_vencimiento');
                },
            ]);

        if ($buscar !== '') {
            $clientesQuery->where(function ($query) use ($buscar) {
                $query->where('nombres', 'like', '%' . $buscar . '%')
                    ->orWhere('apellidos', 'like', '%' . $buscar . '%')
                    ->orWhere('numero_documento', 'like', '%' . $buscar . '%')
                    ->orWhere('celular', 'like', '%' . $buscar . '%');
            });
        }

        $clientes = $clientesQuery
            ->get()
            ->map(function ($cliente) {
                $pagosVencidos = $cliente->prestamos
                ->flatMap->pagos
                ->sortBy('fecha_vencimiento')
                ->values();

                $montoVencido = $pagosVencidos->sum(function ($pago) {
                    return max(((float) $pago->monto_cuota - ((float) $pago->monto_pagado)), 0);
                });

                $cliente->cuotas_vencidas_total = $pagosVencidos->count();
                $cliente->monto_vencido_total = $montoVencido;
                $cliente->primer_vencimiento = optional($pagosVencidos->first())->fecha_vencimiento;

                return $cliente;
            })
            ->sortBy('primer_vencimiento')
            ->values();



        //Logica para mostrar las notificaciones
        return view('admin.notificaciones.index', compact('clientes', 'ajuste', 'buscar'));
    }
}
