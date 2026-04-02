<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PagoParcial;
use App\Models\Prestamo;
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

        $total_pagado = PagoParcial::where('pago_id', $request->input('pago_id'))->sum('monto_pagado');
        if ($total_pagado >= $request->input('monto_total_de_la_cuota')) {
            $pago = Pago::findOrFail($request->pago_id);
            $pago->metodo_pago = "Pago parcial";
            $pago->fecha_cancelado = $request->fecha_pago;
            $pago->monto_total_pagado = $request->monto_total_de_la_cuota;
            $pago->estado = 'pagado';
            $pago->save();
        $cuotasPendientes = $pago->prestamo->pagos->where('estado', 'pendiente')->count();

        if($cuotasPendientes == 0) {
            $prestamo = $pago->prestamo;
            $prestamo->estado = 'pagado';
            $prestamo->save();
        }
    }

        return redirect()->back()
            ->with('mensaje', 'Pago parcial registrado exitosamente')
            ->with('icono', 'success');
    }

    public function destroy(Request $request, $id)
    {
        $montoTotalPagadoValue = $request->input('monto_total_pagado');
        $pagoParcial = PagoParcial::findOrFail($id);
        $pago = Pago::findOrFail($pagoParcial->pago_id);
        $prestamo = Prestamo::findOrFail($pago->prestamo_id);

        //Eliminar pago parcial
        $pagoParcial->delete();

        //Actualizar estado del pago a pendiente
        $total_pagado = PagoParcial::where('pago_id', $pago->id)->sum('monto_pagado');

        if ($total_pagado < $montoTotalPagadoValue) {
            $pago->metodo_pago = '-';
            $pago->fecha_cancelado = null;
            $pago->monto_total_pagado = 0;
            $pago->estado = 'pendiente';
            $pago->save();
        }

        //actualizar estado del prestamo a pendiente
        $cuotasPendientes = $pago->prestamo->pagos->where('estado', 'pendiente')->count();
        if($cuotasPendientes > 0) {
            $prestamo = $pago->prestamo;
            $prestamo->estado = 'pendiente';
            $prestamo->save();
        }

        return redirect()->back()
            ->with('mensaje', 'Pago parcial eliminado exitosamente')
            ->with('icono', 'success');
    }
}
