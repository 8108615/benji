<?php

namespace App\Http\Controllers;

use App\Models\PagoParcial;
use Illuminate\Http\Request;

class PagoParcialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pago_id' => 'required|exists:pagos,id',
            'monto_total_de_la_cuota' => 'required',
            'monto_pagado' => 'required',
            'fecha_pago' => 'required|date',
            'detalle_pago' => 'nullable|string|max:255',
        ]);

        $pagoParcial = new PagoParcial();
        $pagoParcial->pago_id = $request->input('pago_id');
        $pagoParcial->monto_total_de_la_cuota = $request->input('monto_total_de_la_cuota');
        $pagoParcial->monto_pagado = $request->input('monto_pagado');
        $pagoParcial->fecha_pago = $request->input('fecha_pago');
        $pagoParcial->detalle_pago = $request->input('detalle_pago');
        $pagoParcial->save();

        return redirect()->back()
            ->with('mensaje', 'Pago parcial registrado exitosamente')
            ->with('icono', 'success');
    }
}
