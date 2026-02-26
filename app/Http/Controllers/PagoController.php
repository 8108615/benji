<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

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

        return redirect()->route('admin.prestamos.show', $pago->prestamo_id)
            ->with('mensaje', 'Pago registrado exitosamente.')
            ->with('icono', 'success');
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
    public function destroy(Pago $pago)
    {
        //
    }
}
