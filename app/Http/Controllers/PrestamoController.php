<?php

namespace App\Http\Controllers;

use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ajuste = Ajuste::first();
        $prestamos = Prestamo::with('cliente', 'categoria', 'pagos')->paginate(10);
        //return response()->json($prestamos);
        return view('admin.prestamos.index', compact('prestamos', 'ajuste'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $categorias = Categoria::all();
        $ajuste = Ajuste::first();
        return view('admin.prestamos.create', compact('clientes', 'categorias', 'ajuste'));
    }

    public function contrato($id)
    {
        $ajuste = Ajuste::first();
        $prestamo = Prestamo::with('cliente', 'categoria', 'pagos')->findOrFail($id);
        $cliente = $prestamo->cliente;
        $pagos = $prestamo->pagos()->orderBy('fecha_vencimiento')->get();

        $totalCapital = $prestamo->monto_prestado;
        $totalInteres = $prestamo->monto_interes_total;
        $totalCuotas = $prestamo->monto_total_a_pagar;

        $pdf = Pdf::loadView('admin.prestamos.contrato', compact('prestamo', 'ajuste', 'cliente', 'pagos', 'totalCapital', 'totalInteres', 'totalCuotas'));
        $pdf->setOption([
            'dpi' => 120,
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial Narrow',
        ]);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('contrato_prestamo_' . $prestamo->id . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json($request->all());
        $request->validate([
            'monto_prestado' => 'required|numeric|min:0.01',
            'cliente_id' => 'required|exists:clientes,id',
            'categoria_id' => 'required|exists:categorias,id',
            'tasa_interes' => 'required|numeric|min:0',
            'modalidad_pago' => 'required',
            'modalidad_amortizacion' => 'required',
            'nro_cuotas' => 'required|integer|min:1',
            'monto_interes_total' => 'required|numeric|min:0',
            'monto_total_a_pagar' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'cuotas_json' => 'required|json',
        ]);

        DB::beginTransaction();

        try {

            $prestamo = new Prestamo();
            $prestamo->monto_prestado = $request->monto_prestado;
            $prestamo->cliente_id = $request->cliente_id;
            $prestamo->categoria_id = $request->categoria_id;
            $prestamo->tasa_interes = $request->tasa_interes;
            $prestamo->modalidad_pago = $request->modalidad_pago;
            $prestamo->modalidad_amortizacion = $request->modalidad_amortizacion;
            $prestamo->nro_cuotas = $request->nro_cuotas;
            $prestamo->monto_interes_total = $request->monto_interes_total;
            $prestamo->monto_total_a_pagar = $request->monto_total_a_pagar;
            $prestamo->fecha_inicio = $request->fecha_inicio;
            $prestamo->estado = 'Pendiente';
            $prestamo->save();

            $cuotas = json_decode($request->cuotas_json, true) ?: [];
            if (count($cuotas) !== (int) $request->nro_cuotas) {
                throw new \Exception('El número de cuotas no coincide con el número de cuotas generado.');
            }

            $numero_cuota = 1;
            foreach ($cuotas as $cuota) {
                $pago = new Pago();
                $pago->prestamo_id = $prestamo->id;
                $pago->fecha_vencimiento = $cuota['fecha_vencimiento'];
                $pago->saldo_capital = $cuota['saldo_capital'];
                $pago->monto_capital = $cuota['monto_capital'];
                $pago->monto_interes = $cuota['monto_interes'];
                $pago->monto_cuota = $cuota['monto_cuota'];
                $pago->metodo_pago = '-';
                $pago->referencia_pago = 'Cuota: ' . $numero_cuota;
                $pago->save();
                $numero_cuota++;
            }

            DB::commit();

            return redirect()->route('admin.prestamos.index')
                ->with('mensaje', 'Préstamo creado exitosamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al crear el préstamo: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ajuste = Ajuste::first();
        $prestamo = Prestamo::with('cliente', 'categoria', 'pagos')->findOrFail($id);

        $liquidacion = $this->calcularLiquidacion($prestamo, $ajuste);

        //return response()->json([$liquidacion]);

        return view('admin.prestamos.show', compact('prestamo','ajuste', 'liquidacion'));
    }

    public function calcularLiquidacion(Prestamo $prestamo, Ajuste $ajuste){
        $hoy = Carbon::today();

        $pagosPendientes = $prestamo->pagos->where('estado', 'pendiente')->sortBy('fecha_vencimiento');

        $cuotaActual = $pagosPendientes->first();
        $totalCapitalRestante = $cuotaActual ? ($cuotaActual->saldo_capital ?? $pagosPendientes->sum('monto_capital')) : 0;
        $totalCuotasRestantes = $pagosPendientes->sum('monto_cuota');

        //Inicializamos variables para el calculo
        $interesDevengado = 0;
        $diasDevengados = 0;
        $diasMora = 0;
        $moraDevengada = 0;

        if($cuotaActual){
            $fechaVencimiento = $cuotaActual->fecha_vencimiento ? Carbon::parse($cuotaActual->fecha_vencimiento)->startOfDay() : null;
            $montoInteresCuota = $cuotaActual->monto_interes ?? 0;

            $ultimoPagoPagado = $prestamo->pagos->where('estado', 'pagado')->sortByDesc('fecha_vencimiento')->first();
            $periodoInicio = $ultimoPagoPagado && $ultimoPagoPagado->fecha_vencimiento
                ? Carbon::parse($ultimoPagoPagado->fecha_vencimiento)->startOfDay() : Carbon::parse($prestamo->fecha_inicio)->startOfDay();
                //Caso 1: Cuota vencida
                if($fechaVencimiento && $fechaVencimiento->lt($hoy)){
                    $interesDevengado = $montoInteresCuota;

                    //dias transcurrido desde el vencimiento
                    $diasDevengados = $fechaVencimiento->diffInDays($hoy);

                    //dias de mora luego de descontar los dias de gracia
                    $diasMora = max(0, $diasDevengados - $ajuste->dias_gracia);
                }
                //caso 2: Cuota no vencida aun
                if($fechaVencimiento && $periodoInicio && $fechaVencimiento->gt($hoy)){
                    $periodoLength = max(1, $periodoInicio->diffInDays($fechaVencimiento)); // Evitamos división por cero
                    $diasTranscurridos = max(0, $periodoInicio->diffInDays($hoy));
                    $diasDevengados = min($periodoLength, $diasTranscurridos);
                    $interesDevengado = round(($montoInteresCuota * $diasDevengados) / $periodoLength, 2);

                    $diasMora = 0; // No hay mora si la cuota no ha vencido
                }

                //Calculo de mora devengada
                $tasaMoraDiaria = $ajuste->mora / 100;
                $moraCuotaActual = $cuotaActual->monto_cuota ?? 0;
                $moraDevengada = round($moraCuotaActual * $tasaMoraDiaria * $diasMora, 2);
        }

        //total a pagar para liquidar el prestamo hoy
        $totalLiquidacion = round($totalCapitalRestante + $interesDevengado + $moraDevengada, 2);

        return[
            'pagos_pendientes' => $pagosPendientes,
            'capital_restante' => round($totalCapitalRestante, 2),
            'interes_devengado' => $interesDevengado,
            'dias_devengados' => $diasDevengados,
            'dias_mora' => $diasMora,
            'tasa_mora_diaria' => $tasaMoraDiaria ?? 0,
            'dias_gracia' => $ajuste->dias_gracia,
            'monto_cuota_actual' => $cuotaActual->monto_cuota ?? 0,
            'mora_devengada' => $moraDevengada,
            'cuotas_restantes' => round($totalCuotasRestantes, 2),
            'total_liquidacion' => $totalLiquidacion,
        ];
    }

    public function liquidar($id){
        $prestamo = Prestamo::with('pagos')->findOrFail($id);
        $ajuste = Ajuste::first();
        $liquidacion = $this->calcularLiquidacion($prestamo, $ajuste);

        DB::beginTransaction();

        try {
            $pagosPendientes = $prestamo->pagos->where('estado', 'pendiente')->sortBy('fecha_vencimiento')->values();
            $totalLiquidacion = $liquidacion['total_liquidacion'] ?? 0;
            $sumaCapital = $pagosPendientes->sum('monto_capital');
            $diferencia = round($totalLiquidacion - $sumaCapital, 2);

            foreach ($pagosPendientes as $index => $pago) {
                $pago->monto_interes = 0; //se elimina el interes devengado de cada cuota pendiente
                $pago->monto_cuota = $pago->monto_capital; //el monto de cada cuota pendiente se reduce al capital restante
                $pago->metodo_pago = 'Efectivo';
                $pago->fecha_cancelado = Carbon::today();
                $pago->monto_total_pagado = $pago->monto_capital;
                $pago->estado = 'Pagado';

                if($index === 0){
                     $pago->monto_total_pagado = round($pago->monto_capital + $diferencia, 2); // Ajustamos el primer pago con la diferencia
                }else{
                    $pago->monto_total_pagado = $pago->monto_capital; // Los demas pagos se pagan al capital
                }

                $pago->save();

            }

            $prestamo->estado = 'pagado';
            $prestamo->save();

            DB::commit();

            return redirect()->route('admin.prestamos.show', $prestamo->id)
                ->with('mensaje', 'Préstamo liquidado exitosamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al liquidar el préstamo: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $prestamo = Prestamo::with('cliente', 'categoria', 'pagos')->findOrFail($id);
        $clientes = Cliente::all();
        $categorias = Categoria::all();
        $ajuste = Ajuste::first();
        return view('admin.prestamos.edit', compact('prestamo', 'clientes', 'categorias', 'ajuste'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //return response()->json($request->all());
        $request->validate([
            'monto_prestado' => 'required|numeric|min:0.01',
            'cliente_id' => 'required|exists:clientes,id',
            'categoria_id' => 'required|exists:categorias,id',
            'tasa_interes' => 'required|numeric|min:0',
            'modalidad_pago' => 'required',
            'modalidad_amortizacion' => 'required',
            'nro_cuotas' => 'required|integer|min:1',
            'monto_interes_total' => 'required|numeric|min:0',
            'monto_total_a_pagar' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'cuotas_json' => 'required|json',
        ]);

        DB::beginTransaction();

        try {
            $prestamo = Prestamo::findOrFail($id);
            $prestamo->monto_prestado = $request->monto_prestado;
            $prestamo->cliente_id = $request->cliente_id;
            $prestamo->categoria_id = $request->categoria_id;
            $prestamo->tasa_interes = $request->tasa_interes;
            $prestamo->modalidad_pago = $request->modalidad_pago;
            $prestamo->modalidad_amortizacion = $request->modalidad_amortizacion;
            $prestamo->nro_cuotas = $request->nro_cuotas;
            $prestamo->monto_interes_total = $request->monto_interes_total;
            $prestamo->monto_total_a_pagar = $request->monto_total_a_pagar;
            $prestamo->fecha_inicio = $request->fecha_inicio;
            // No se actualiza el estado aquí, se mantiene el estado actual
            $prestamo->save();

            // Aqui podrias agregar ligica para actualizar los pagos asociados si es necesario
            $prestamo->pagos()->delete(); // Elimina los pagos existentes

            $numero_cuota = 1;
            $cuotas = json_decode($request->cuotas_json, true) ?: [];
            if (count($cuotas) !== (int) $request->nro_cuotas) {
                throw new \Exception('El número de cuotas no coincide con el número de cuotas generado.');
            }

            foreach ($cuotas as $cuota) {
                $pago = new Pago();
                $pago->prestamo_id = $prestamo->id;
                $pago->fecha_vencimiento = $cuota['fecha_vencimiento'];
                $pago->saldo_capital = $cuota['saldo_capital'];
                $pago->monto_capital = $cuota['monto_capital'];
                $pago->monto_interes = $cuota['monto_interes'];
                $pago->monto_cuota = $cuota['monto_cuota'];
                $pago->metodo_pago = '-';
                $pago->referencia_pago = 'Cuota: ' . $numero_cuota;
                $pago->save();
                $numero_cuota++;
            }

            DB::commit();

            return redirect()->route('admin.prestamos.index')
                ->with('mensaje', 'Préstamo Actualizado exitosamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al Actualizar el préstamo: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $prestamo = Prestamo::findOrFail($id);
            $prestamo->delete();
            return redirect()->route('admin.prestamos.index')
                ->with('mensaje', 'Préstamo Eliminado exitosamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al Eliminar el préstamo: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}
