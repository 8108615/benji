<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Pago;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //response()->json($request->all());
        $request->validate([
            'pago_id' => 'required|exists:pagos,id',
            'metodo_pago' => 'required|string|max:255',
            'fecha_cancelacion' => 'required|date',
            'monto_total_pagado' => 'required|numeric|min:0',
        ]);
        $pago = Pago::findOrFail($request->pago_id);
        $pago->metodo_pago = $request->metodo_pago;
        $pago->fecha_cancelado = $request->fecha_cancelacion;
        $pago->monto_total_pagado = $request->monto_total_pagado;
        $pago->estado = 'pagado';
        $pago->save();

        $cuotasPendientes = $pago->prestamo->pagos->where('estado', 'pendiente')->count();

        if($cuotasPendientes == 0) {
            $prestamo = $pago->prestamo;
            $prestamo->estado = 'pagado';
            $prestamo->save();
        }

        return redirect()->route('admin.prestamos.show', $pago->prestamo_id)
            ->with('mensaje', 'Pago registrado exitosamente.')
            ->with('icono', 'success');
    }

    public function comprobante($id)
    {
        $ajuste = Ajuste::first();
        $pago = Pago::findOrFail($id);
        $prestamo = $pago->prestamo;
        $cliente = $prestamo->cliente;

        $total_pagos = Pago::where('prestamo_id', $prestamo->id)->count();
        $numero_secuencia = Pago::where('prestamo_id', $prestamo->id)
            ->where('id', '<=', $pago->id)->count();
        $numero_secuencia = $numero_secuencia;
        $numero_pago = "$numero_secuencia de $total_pagos";

        $meses = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre',
        ];

        $fecha_pago_programado = $pago->fecha_vencimiento;
        $timestamp = strtotime($fecha_pago_programado);
        $dia = date('j', $timestamp);
        $mes = date('F', $timestamp);
        $ano = date('Y', $timestamp);
        $mes_espanol = $meses[$mes];
        $fecha_pago_programado = $dia . " de ". $mes_espanol . " de ". $ano;

        $fecha_cancelado = $pago->fecha_cancelado;
        $timestamp = strtotime($fecha_cancelado);
        $dia = date('j', $timestamp);
        $mes = date('F', $timestamp);
        $ano = date('Y', $timestamp);
        $mes_espanol = $meses[$mes];
        $fecha_cancelado = $dia . " de ". $mes_espanol . " de ". $ano;


        $pdf = Pdf::loadView('admin.pagos.comprobante', compact('pago', 'ajuste', 'prestamo', 'cliente','numero_pago', 'fecha_pago_programado', 'fecha_cancelado'));
        $pdf->setOption([
            'dpi' => 120,
            'defaultPaperSize' => [0, 0, 226.77, 0],
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial Narrow',
        ]);
        $pdf->setPaper([0, 0, 226.77, 999999], 'portrait');
        return $pdf->stream('comprobante_pago_' . $pago->id . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->metodo_pago = '-';
        $pago->fecha_cancelado = null;
        $pago->monto_total_pagado = 0;
        $pago->estado = 'pendiente';
        $pago->save();

        $pago->pagosParciales()->delete();

        $cuotasPendientes = $pago->prestamo->pagos->where('estado', 'pendiente')->count();
        if($cuotasPendientes > 0) {
            $prestamo = $pago->prestamo;
            $prestamo->estado = 'pendiente';
            $prestamo->save();
        }

        return redirect()->route('admin.prestamos.show', $pago->prestamo_id)
            ->with('mensaje', 'Pago borrado Exitosamente.')
            ->with('icono', 'success');
    }
}
