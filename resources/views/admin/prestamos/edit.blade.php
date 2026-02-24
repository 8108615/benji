@php
    $divisa = $ajuste->divisa ?? '$';
    $montoPrestado = old('monto_prestado', $prestamo->monto_prestado ?? 0);
    $montoInteresTotal = old('monto_interes_total', $prestamo->monto_interes_total ?? 0);
    $montoTotalPagar = old('monto_total_a_pagar', $prestamo->monto_total_a_pagar ?? 0);
    $nroCuotas = old('nro_cuotas', $prestamo->nro_cuotas ?? 0);
    $cuotaPromedio = $nroCuotas ? $montoTotalPagar / $nroCuotas : 0;

    $cuotasJson = old('cuotas_json');
    if ($cuotasJson) {
        $cuotas = json_decode($cuotasJson, true) ?: [];
    } else {
        $cuotas = $prestamo->pagos
            ->map(function ($pago) {
                return [
                    'fecha_vencimiento' => $pago->fecha_vencimiento
                        ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('Y-m-d')
                        : null,
                    'saldo_capital' => $pago->saldo_capital,
                    'monto_capital' => $pago->monto_capital,
                    'monto_interes' => $pago->monto_interes,
                    'monto_cuota' => $pago->monto_cuota,
                ];
            })
            ->values()
            ->toArray();
        $cuotasJson = json_encode($cuotas);
    }
    $totalSaldo = collect($cuotas)->sum('saldo_capital');
    $totalCapital = collect($cuotas)->sum('monto_capital');
    $totalInteres = collect($cuotas)->sum('monto_interes');
    $totalCuota = collect($cuotas)->sum('monto_cuota');
@endphp
<x-layouts.app title="Editar Préstamo">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Préstamo</flux:heading I <p
            class="text-slate-500 dark:text-neutral-400">Actualiza la información del préstamo.</p>
        <br>
        <flux separator variant="subtle" />
    </div>
    <form action="{{ url('/admin/prestamo/' . $prestamo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div
            class="bg-white dark:bg-neutral-800 border-2 border-gray-388 dark:border-gray-500 rounded-lg shadow-16 16px 40px-18px rgba(0,0,0,0.3)]">
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading level="2" size="lg" class="text-blue-600">Calcular Préstamo
                        </flux:heading <flux:button type="button" id="btn-calcular" variant="primary" color="green"
                            class="px-5">
                        </flux:button>
                        <i class="fa fa-calculator mr-2"></i> Recalcular Préstamo
                        </flux:button>
                    </div>
                    <div class="flex-1">
                        <flux:label>Monto del préstamo <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="monto_prestado" type="number" step="0.01" placeholder="5000.00"
                            icon="currency-dollar" required
                            value="{{ old('monto_prestado', $prestamo->monto_prestado ?? 0) }}" />
                        <flux:error name="monto_prestado" />
                    </div>
                    <div class="flex-1">
                        <flux:label>Tasa de interés (%) <span class="text-red-500">(*)</span>
                        </flux:label>
                        I
                        <div class="flex gap-2">
                            <flux: input name="tasa_interes" type="number" step="0.01" placeholder="10" required
                                value="{{ old('tasa_interes', $prestamo->tasa_interes ?? '') }}" />
                        </div>
                        <flux:error name="tasa interes" />
                    </div>
                    <div class="flex-1">
                        <flux:label>Modalidad de pago <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="modalidad_pago" required>
                            <option value="" disabled
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') ? '' : 'selected' }}>
                                Seleccione...</option>
                            <option value="Semanal"
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Semanal' ? 'selected' : '' }}>
                                Semanal</option>
                            <option value="Quincenal"
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Quincenal' ? 'selected' : '' }}>
                                Quincenal</option>
                            <option value="Mensual"
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Mensual' ? 'selected' : '' }}>
                                Mensual</option>
                            <option value="Bimestral"
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Bimestral' ? 'selected' : '' }}>
                                Bimestral</option>
                            I
                            <option value="Trimestral"
                                {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Trimestral' ? 'selected' : '' }}>
                                Trimestral
                            </option>
                        </flux:select>
                        flux:error name="modalidad_pago" />
                    </div>
                </div>
                <br>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <flux:label>Modalidad Amortización <span class="text-red-500">(*)</span></flux:label>
                        <flux:select name="modalidad_amortizacion" required>
                            <option value="" disabled
                                {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') ? '' : 'selected' }}>
                                Seleccione...</option>
                            <option value="Francés"
                                {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') == 'Francés' ? 'selected' : '' }}>
                                Cuota Fija (Sistema Francés)</option>
                            <option value="Aleman"
                                {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') == 'Aleman' ? 'selected' : '' }}>
                                Alemán (Cuotas
                                decrecientes)</option>
                            <option value="Americano"
                                {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') == 'Americano' ? 'selected' : '' }}>
                                Americano
                                (Pago al final)</option>
                        </flux:select>
                        I
                        <flux:error name="modalidad_amortizacion" />
                    </div>
                    <div class="flex-1">
                        <flux:label>Nro de cuotas <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="nro_cuotas" type="number" placeholder="12" icon="calculator" required
                            value="{{ old('nro_cuotas', $prestamo->nro_cuotas ?? '') }}" />
                        <flux:error name="nro_cuotas" />
                    </div>
                    <div class="flex-1">
                        <flux:label>Fecha de inicio <span class="text-red-500">(*)</span></flux:label>
                        <flux:input name="fecha_inicio" type="date" required
                            value="{{ old('fecha_inicio', $prestamo->fecha_inicio ? \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('Y-m-d') : date('Y-m-d')) }}" />
                        <flux:error name="fecha_inicio" />
                    </div>
                </div>
            </div>

            {{--  Resultados del Cálculo  --}}
            <div id="resultados-container" class="mb-6">
                <flux: separator variant="subtle" class="my-6" />
                <flux:heading level="2" size="lg" class="mb-4 text-green-608">Resultados del Cálculo
                </flux:heading>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div
                        class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-1g border border-amber-200 dark:border-amber-808">
                        <p class="text-sm text-gray-606 dark: text-gray-488 mb-1">Monto Prestado</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400" id="resultado_monto">
                            {{ $divisa }}
                            {{ number_format($montoPrestado, 2) }}</p>
                        I
                    </div>
                    <div
                        class="p-4 bg-blue-50 dark:bg-blue-986/20 rounded-1g border border-blue-200 dark:border-blue-886">
                        <p class="text-sm text-gray-606 dark: text-gray-400 mb-1">Interés Total</p>
                        <input type="text" id="monto_interes_total" name="monto_interes_total" hidden
                            value="{{ $montoInteresTotal }}">
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-486" id="resultado_interes">
                            {{ $divisa }}
                            {{ number_format($montoInteresTotal, 2) }}</p>
                    </div>
                    <div
                        class="p-4 bg-green-58 dark:bg-green-900/20 rounded-1g border border-green-208 dark:border-green-888">
                        <p class="text-sm text-gray-606 dark:text-gray-480 mb-1">Total a Pagar</p>
                        <input type="text" id="monto_total_a_pagar" name="monto_total_a_pagar" hidden
                            value="{{ $montoTotalPagar }}">
                        <input type="hidden" id="cuotas_json" name="cuotas_json" value="{{ json_encode($cuotas) }}">
                        <flux:error name="cuotas_json" />
                        <p class="text-2xl font-bold text-green-608 dark:text-green-488" id="resultado_total">
                            {{ $divisa }}
                            {{ number_format($montoTotalPagar, 2) }}</p>
                    </div>
                    <div
                        class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cuota Promedio</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="resultado_cuota">
                            {{ $divisa }}
                            {{ number_format($cuotaPromedio, 2) }}</p>
                    </div>
                </div>

                {{-- Tabla de Amortización --}}
                <flux:heading level="3" size="md" class="mb-4 text-gray-706 dark:text-gray-300">Tabla de
                    Amortización
                </flux:heading>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-600">
                    <table class="min-w-full text-sm text-gray-706 dark:text-black-108">
                        <thead class="bg-gray-100 dark:bg-neutral-968">
                            <tr class="text-left text-gray-706 dark:text-gray-106 tont-semibold">
                                <th class="py-3 px-4 font-semibold">Nro</th>
                                <th class="py-3 px-4 font-semibold">Fecha de Vencimiento</th>
                                <th class="py-3 px-4 font-semibold">Saldo Capital</th>
                                <th class="py-3 px-4 font-semibold">Capital</th>
                                <th class="py-3 px-4 font-semibold">Interés</th>
                                <th class="py-3 px-4 font-semibold">Total Cuota</th>
                        </thead>
                        <tbody id="tabla-amortizacion" class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse ($cuotas as $index => $cuota)
                                <tr>
                                    <td class="py-2 px-4">{{ $index + 1 }}</td>
                                    <td class="py-2 px-4">
                                        {{ empty($cuota['fecha_vencimiento']) ? \Carbon\Carbon::parse($cuota['fecha_vencimiento'])->format('d/m/Y') : 'Sin fecha' }}
                                    </td>
                                    <td class="py-2 px-4">{{ $divisa }}
                                        {{ number_format($cuota['saldo_capital'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-4">{{ $divisa }}
                                        {{ number_format($cuota['monto_capital'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-4">{{ $divisa }}
                                        {{ number_format($cuota['monto_interes'] ?? 0, 2) }}</td>
                                    <td class="py-2 px-4">{{ $divisa }}
                                        {{ number_format($cuota['monto_cuota'] ?? 0, 2) }}
                                    </td>
                                @empty
                                <tr>
                                    <td class="py-4 px-4 text-center text-gray-586" colspan="6">No hay cuotas
                                        disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-100 dark:bg-neutral-900">
                            <tr class="text-left text-gray-700 dark:text-gray-100 font-semibold">
                                <td class="py-3 px-4" colspan="2">Totales</td>
                                <td class="py-3 px-4" id="total_saldo">{{ $divisa }}
                                    {{ number_format($totalSaldo, 2) }}</td>
                                <td class="py-3 px-4" id="total_capital">{{ $divisa }}
                                    {{ number_format($totalCapital, 2) }}</td>
                                <td class="py-3 px-4" id="total_interes">{{ $divisa }}
                                    {{ number_format($totalInteres, 2) }}</td>
                                <td class="py-3 px-4" id="total_cuota">{{ $divisa }}
                                    {{ number_format($totalCuota, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
