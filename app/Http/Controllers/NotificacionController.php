<?php

namespace App\Http\Controllers;

use App\Mail\CuotasVencidasMail;
use App\Models\Ajuste;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar'));
        $ajuste = Ajuste::first();
        $hoy = now()->toDateString();

        $filtroPagosVencidos = function ($query) use ($hoy) {
            $query->where('estado', 'pendiente')
                ->whereDate('fecha_vencimiento', '<', $hoy);
        };

        $clientesQuery = Cliente::query()
            ->whereHas('prestamos.pagos', $filtroPagosVencidos)
            ->with([
                'prestamos.pagos' => function ($query) use ($filtroPagosVencidos) {
                    $filtroPagosVencidos($query);
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
                    return max(((float) $pago->monto_cuota - ((float) $pago->monto_total_pagado)), 0);
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

    public function notificarEmail(Cliente $cliente)
    {
        $email = $cliente->user->email;

        if (!$email) {
            return redirect()->route('admin.notificaciones.index')
                ->with('mensaje', 'El cliente no tiene un Email registrado para enviar la notificacion.')
                ->with('icono', 'error');
        }

        $ajuste = Ajuste::first();
        $resumen = $this->obtenerResumenVencido($cliente, $ajuste);

        try {
            Mail::to($email)->send(new CuotasVencidasMail($resumen));
            return redirect()->route('admin.notificaciones.index')
                ->with('mensaje', 'Notificación por email enviada correctamente al cliente: ' . $cliente->nombres . ' ' . $cliente->apellidos)
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.notificaciones.index')
                ->with('mensaje', 'Error al enviar la notificación por email: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function obtenerResumenVencido(Cliente $cliente, ?Ajuste $ajuste): array
    {
        $pagosVencidos =  Pago::query()
            ->whereHas('prestamo', function ($query) use ($cliente) {
                $query->where('cliente_id', $cliente->id);
            })
            ->where('estado', 'pendiente')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->orderBy('fecha_vencimiento')
            ->get();

        $montoVencidoTotal = $pagosVencidos->sum(function ($pago){
            return max(((float) $pago->monto_cuota) - ((float) $pago->monto_total_pagado), 0);
        });

        $primerVencimiento = optional($pagosVencidos->first())->fecha_vencimiento;

        return [
            'cliente' => $cliente,
            'divisa' => $ajuste->divisa ?? '$',
            'cuotas_vencidas_total' => $pagosVencidos->count(),
            'monto_vencido_total' => $montoVencidoTotal,
            'primer_vencimiento' => $primerVencimiento,
            'primer_vencimiento_formateado' => $primerVencimiento ? Carbon::parse($primerVencimiento)->format('d/m/Y') : '-',
            'fecha_actual' => now()->format('d/m/Y'),
        ];
    }
}
