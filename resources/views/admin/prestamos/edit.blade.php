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
        <flux:heading size="xl" level="1">Editar Préstamo</flux:heading>
        <p class="text-slate-500 dark:text-neutral-400">Actualiza la información del préstamo.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <form action="{{ url('/admin/prestamo/' . $prestamo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div
            class="bg-white dark:bg-neutral-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] dark:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.7)]">

            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading level="2" size="lg" class="text-blue-600">Calcular Préstamo
                        </flux:heading>
                        <flux:button type="button" id="btn-calcular" variant="primary" color="green" class="px-5">
                            <i class="fas fa-calculator mr-2"></i> Calcular Préstamo
                        </flux:button>
                    </div>
                    <div class="flex gap-4">

                        <div class="flex-1">
                            <flux:label>Monto del préstamo <span class="text-red-500">(*)</span></flux:label>
                            <flux:input name="monto_prestado" type="number" step="0.01" placeholder="5000.00"
                                icon="currency-dollar" required
                                value="{{ old('monto_prestado', $prestamo->monto_prestado ?? '') }}" />
                            <flux:error name="monto_prestado" />
                        </div>

                        <div class="flex-1">
                            <flux:label>Tasa de interés (%) <span class="text-red-500">(*)</span>
                            </flux:label>
                            <div class="flex gap-2">
                                <flux:input name="tasa_interes" type="number" step="0.01" placeholder="10" required
                                    value="{{ old('tasa_interes', $prestamo->tasa_interes ?? '') }}" />
                            </div>
                            <flux:error name="tasa_interes" />
                        </div>

                        <div class="flex-1">
                            <flux:label>Modalidad de pago <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="modalidad_pago" required>
                                <option value="" disabled
                                    {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') ? '' : 'selected' }}>
                                    Seleccione...</option>
                                <option value="Semanal" {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Semanal' ? 'selected' : '' }}>
                                    Semanal</option>
                                <option value="Quincenal" {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Quincenal' ? 'selected' : '' }}>
                                    Quincenal</option>
                                <option value="Mensual" {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Mensual' ? 'selected' : '' }}>
                                    Mensual</option>
                                <option value="Bimestral" {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Bimestral' ? 'selected' : '' }}>
                                    Bimestral</option>
                                <option value="Trimestral"
                                    {{ old('modalidad_pago', $prestamo->modalidad_pago ?? '') == 'Trimestral' ? 'selected' : '' }}>Trimestral
                                </option>
                            </flux:select>
                            <flux:error name="modalidad_pago" />
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
                                    Cuota Fija
                                    (Sistema Francés)</option>
                                <option value="Alemán"
                                    {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') == 'Alemán' ? 'selected' : '' }}>
                                    Alemán (Cuotas
                                    decrecientes)</option>
                                <option value="Americano"
                                    {{ old('modalidad_amortizacion', $prestamo->modalidad_amortizacion ?? '') == 'Americano' ? 'selected' : '' }}>
                                    Americano
                                    (Pago al final)</option>
                            </flux:select>
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
                                value="{{ old('fecha_inicio', $prestamo->fecha_inicio ? \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('Y-m-d') : date('Y-m-d') ) }}" />
                            <flux:error name="fecha_inicio" />
                        </div>
                    </div>

                </div>

                <!-- Resultados del Cálculo -->
                <div id="resultados-container" class="hidden mb-6">
                    <flux:separator variant="subtle" class="my-6" />
                    <flux:heading level="2" size="lg" class="mb-4 text-green-600">Resultados del Cálculo
                    </flux:heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div
                            class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Monto Prestado</p>
                            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400" id="resultado_monto">
                                {{ $divisa }}
                                {{ number_format($prestamo->monto_prestado, 2) }}</p>
                        </div>
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Interés Total</p>
                            <input type="text" id="monto_interes_total" name="monto_interes_total" hidden
                                value="{{ $montoInteresTotal }}">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="resultado_interes">
                                {{ $divisa }}
                                {{ number_format($montoInteresTotal, 2) }}</p>
                        </div>
                        <div
                            class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total a Pagar</p>
                            <input type="text" id="monto_total_a_pagar" name="monto_total_a_pagar" hidden
                                value="{{ $montoTotalPagar }}">
                            <input type="hidden" id="cuotas_json" name="cuotas_json" value="{{ $cuotasJson }}">
                            <flux:error name="cuotas_json" />
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="resultado_total">
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

                    <!-- Tabla de Amortización -->
                    <flux:heading level="3" size="md" class="mb-4 text-gray-700 dark:text-gray-300">Tabla
                        de Amortización
                    </flux:heading>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-600">
                        <table class="min-w-full text-sm text-gray-700 dark:text-black-100">
                            <thead class="bg-gray-100 dark:bg-neutral-900">
                                <tr class="text-left text-gray-700 dark:text-gray-100 font-semibold">
                                    <th class="py-3 px-4 font-semibold">Nro</th>
                                    <th class="py-3 px-4 font-semibold">Fecha de Vencimiento</th>
                                    <th class="py-3 px-4 font-semibold">Saldo Capital</th>
                                    <th class="py-3 px-4 font-semibold">Capital</th>
                                    <th class="py-3 px-4 font-semibold">Interés</th>
                                    <th class="py-3 px-4 font-semibold">Total Cuota</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-amortizacion" class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @forelse ($cuotas as $index => $cuota)
                                    <tr>
                                        <td class="py-2 px-4">{{ $index + 1 }}</td>
                                        <td class="py-2 px-4">
                                            {{ !empty($cuota['fecha_vencimiento']) ? \Carbon\Carbon::parse($cuota['fecha_vencimiento'])->format('d/m/Y') :  '-' }}
                                        </td>
                                        <td class="py-2 px-4">{{ $divisa }}
                                            {{ number_format($cuota['saldo_capital'] ?? 0, 2) }}</td>
                                        <td class="py-2 px-4">{{ $divisa }}
                                            {{ number_format($cuota['monto_capital'] ?? 0, 2) }}</td>
                                        <td class="py-2 px-4">{{ $divisa }}
                                            {{ number_format($cuota['monto_interes'] ?? 0, 2) }}</td>
                                        <td class="py-2 px-4">{{ $divisa }}
                                            {{ number_format($cuota['monto_cuota'] ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4 text-center text-gray-500" colspan="6"> No hay Cuotas
                                            Disponibles.</td>
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

        <div
            class="bg-white dark:bg-neutral-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] dark:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.7)]">

            <div class="p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <flux:heading level="2" size="lg" class="text-blue-600">Datos para el préstamo
                        </flux:heading>
                    </div>
                    <div class="flex gap-4">

                        <div class="flex-1">
                            <flux:label>Cliente <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="cliente_id" required>
                                <option value="" disabled
                                    {{ old('cliente_id', $prestamo->cliente_id ?? '') ? '' : 'selected' }}>
                                    Seleccione...</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ old('cliente_id', $prestamo->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->apellidos }} {{ $cliente->nombres }} -
                                        {{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="cliente_id" />
                        </div>

                        <div class="flex-1">
                            <flux:label>Categorías <span class="text-red-500">(*)</span></flux:label>
                            <flux:select name="categoria_id" required>
                                <option value="" disabled
                                    {{ old('categoria_id', $prestamo->categoria_id ?? '') ? '' : 'selected' }}>
                                    Seleccione...</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ old('categoria_id', $prestamo->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="categoria_id" />
                        </div>


                    </div>

                </div>

            </div>

        </div>

        <br>

        <div class="bg-gray-50 dark:bg-neutral-700 border-t border-gray-200 dark:border-gray-700 rounded-b-lg p-6">
            <div class="flex space-x-3">
                <a href="{{ url('/admin/prestamos') }}"
                    class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
                <flux:button variant="primary" type="submit" color="blue" class="px-5 cursor-pointer">
                    <i class="fas fa-save mr-2"></i> Actualizar Préstamo
                </flux:button>
            </div>
        </div>

    </form>

    <script>
        // Elementos del DOM
        const btnCalcular = document.getElementById('btn-calcular');
        const resultadosContainer = document.getElementById('resultados-container');
        const tablaBody = document.getElementById('tabla-amortizacion');
        const resultadoInteres = document.getElementById('resultado_interes');
        const resultadoTotal = document.getElementById('resultado_total');
        const resultadoCuota = document.getElementById('resultado_cuota');
        const resultadoMonto = document.getElementById('resultado_monto');
        const divisa = @json($divisa);
        const totalCuota = document.getElementById('total_cuota');
        const totalInteres = document.getElementById('total_interes');
        const totalCapital = document.getElementById('total_capital');
        const totalSaldo = document.getElementById('total_saldo');
        const montoInteresTotalInput = document.getElementById('monto_interes_total');
        const montoTotalPagarInput = document.getElementById('monto_total_a_pagar');
        const cuotasJsonInput = document.getElementById('cuotas_json');

        // Mapeo de días por período
        const periodDaysMap = {
            Semanal: 7,
            Quincenal: 15
        };

        const periodMonthsMap = {
            Mensual: 1,
            Bimestral: 2,
            Trimestral: 3
        };

        // Función para formatear números con separador de miles
        function formatNumber(num) {
            return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        // Función para agregar días a una fecha
        function addDays(date, days) {
            const result = new Date(date);
            result.setDate(result.getDate() + days);
            return result;
        }

        function addMonths(date, months) {
            const result = new Date(date);
            const day = result.getDate();
            result.setDate(1);
            result.setMonth(result.getMonth() + months);
            const lastDay = new Date(result.getFullYear(), result.getMonth() + 1, 0).getDate();
            result.setDate(Math.min(day, lastDay));
            return result;
        }

        function parseDateInput(value) {
            const parts = value.split('-').map(Number);
            if (parts.length !== 3 || parts.some(Number.isNaN)) return new Date();
            const [year, month, day] = parts;
            return new Date(year, month - 1, day);
        }

        // Función para formatear fecha
        function formatDate(date) {
            return date.toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function formatDateISO(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Sistema Francés - Cuota Fija
        function calcFrances(p, r, n) {
            const cuota = r === 0 ? p / n : p * (r / (1 - Math.pow(1 + r, -n)));
            let saldo = p;
            const rows = [];

            for (let i = 1; i <= n; i++) {
                const saldoInicial = saldo;
                const interes = saldo * r;
                const capital = cuota - interes;
                saldo = Math.max(0, saldo - capital);
                rows.push({
                    saldoInicial: saldoInicial,
                    cuota: cuota,
                    interes: interes,
                    capital: capital,
                    saldo: saldo
                });
            }
            return rows;
        }

        // Sistema Alemán - Cuotas Decrecientes
        function calcAleman(p, r, n) {
            const capitalFijo = p / n;
            let saldo = p;
            const rows = [];

            for (let i = 1; i <= n; i++) {
                const saldoInicial = saldo;
                const interes = saldo * r;
                const cuota = capitalFijo + interes;
                saldo = Math.max(0, saldo - capitalFijo);
                rows.push({
                    saldoInicial: saldoInicial,
                    cuota: cuota,
                    interes: interes,
                    capital: capitalFijo,
                    saldo: saldo
                });
            }
            return rows;
        }

        // Sistema Americano - Pago al Final
        function calcAmericano(p, r, n) {
            let saldo = p;
            const rows = [];

            for (let i = 1; i <= n; i++) {
                const saldoInicial = saldo;
                const interes = saldo * r;
                const capital = i === n ? saldo : 0;
                const cuota = interes + capital;
                if (i === n) saldo = 0;
                rows.push({
                    saldoInicial: saldoInicial,
                    cuota: cuota,
                    interes: interes,
                    capital: capital,
                    saldo: saldo
                });
            }
            return rows;
        }

        // Función principal de cálculo
        function calcular() {
            // Obtener valores del formulario
            const monto = parseFloat(document.querySelector('[name="monto_prestado"]').value) || 0;
            const tasaPorcentaje = parseFloat(document.querySelector('[name="tasa_interes"]').value) || 0;
            const cuotas = parseInt(document.querySelector('[name="nro_cuotas"]').value) || 0;
            const modalidadPago = document.querySelector('[name="modalidad_pago"]').value;
            const modalidadAmort = document.querySelector('[name="modalidad_amortizacion"]').value;
            const fechaInicioInput = document.querySelector('[name="fecha_inicio"]').value;

            // Validar que todos los campos estén completos
            if (!monto || !cuotas || !modalidadPago || !modalidadAmort || !fechaInicioInput) {
                alert('Por favor complete todos los campos requeridos');
                return;
            }

            // Ajustar la tasa según la modalidad de pago
            let tasaPeriodo;
            switch (modalidadPago) {
                case 'Semanal':
                    tasaPeriodo = (tasaPorcentaje / 100) / 52;
                    break;
                case 'Quincenal':
                    tasaPeriodo = (tasaPorcentaje / 100) / 24;
                    break;
                case 'Mensual':
                    tasaPeriodo = (tasaPorcentaje / 100) / 12;
                    break;
                case 'Bimestral':
                    tasaPeriodo = (tasaPorcentaje / 100) / 6;
                    break;
                case 'Trimestral':
                    tasaPeriodo = (tasaPorcentaje / 100) / 4;
                    break;
                default:
                    tasaPeriodo = tasaPorcentaje / 100;
            }

            // Calcular según el sistema de amortización
            let rows = [];
            if (modalidadAmort === 'Francés') {
                rows = calcFrances(monto, tasaPeriodo, cuotas);
            } else if (modalidadAmort === 'Alemán') {
                rows = calcAleman(monto, tasaPeriodo, cuotas);
            } else if (modalidadAmort === 'Americano') {
                rows = calcAmericano(monto, tasaPeriodo, cuotas);
            }

            // Calcular totales
            const totalInteresCalc = rows.reduce((sum, r) => sum + r.interes, 0);
            const totalPagar = rows.reduce((sum, r) => sum + r.cuota, 0);
            const cuotaPromedio = totalPagar / rows.length;
            const totalCapitalCalc = rows.reduce((sum, r) => sum + r.capital, 0);

            // Mostrar resultados
            resultadoMonto.textContent = divisa + ' ' + formatNumber(monto);
            resultadoInteres.textContent = divisa + ' ' + formatNumber(totalInteresCalc);
            resultadoTotal.textContent = divisa + ' ' + formatNumber(totalPagar);
            resultadoCuota.textContent = divisa + ' ' + formatNumber(cuotaPromedio);

            totalCuota.textContent = divisa + ' ' + formatNumber(totalPagar);
            totalInteres.textContent = divisa + ' ' + formatNumber(totalInteresCalc);
            totalCapital.textContent = divisa + ' ' + formatNumber(totalCapitalCalc);
            totalSaldo.textContent = divisa + ' 0.00';

            montoInteresTotalInput.value = totalInteresCalc.toFixed(2);
            montoTotalPagarInput.value = totalPagar.toFixed(2);

            // Generar tabla de amortización
            const fechaInicio = parseDateInput(fechaInicioInput);
            const diasPorPeriodo = periodDaysMap[modalidadPago] || 0;
            const mesesPorPeriodo = periodMonthsMap[modalidadPago] || 0;
            tablaBody.innerHTML = '';
            const cuotasData = [];

            rows.forEach((row, index) => {
                let fechaPago;
                if (mesesPorPeriodo > 0) {
                    fechaPago = addMonths(fechaInicio, mesesPorPeriodo * (index + 1));
                } else {
                    fechaPago = addDays(fechaInicio, diasPorPeriodo * (index + 1));
                }
                cuotasData.push({
                    fecha_vencimiento: formatDateISO(fechaPago),
                    saldo_capital: row.saldoInicial,
                    monto_capital: row.capital,
                    monto_interes: row.interes,
                    monto_cuota: row.cuota
                });
                const tr = document.createElement('tr');
                const isEven = (index + 1) % 2 === 0;
                tr.className = isEven ?
                    'bg-gray-50 dark:bg-neutral-800/80 hover:bg-gray-100 dark:hover:bg-neutral-700' :
                    'bg-white dark:bg-neutral-900/80 hover:bg-gray-100 dark:hover:bg-neutral-700';
                tr.innerHTML = `
                    <td class="py-3 px-4">${index + 1}</td>
                    <td class="py-3 px-4">${formatDate(fechaPago)}</td>
                    <td class=\"py-3 px-4 font-semibold\">${divisa} ${formatNumber(row.saldoInicial)}</td>
                    <td class="py-3 px-4 text-green-600">${divisa} ${formatNumber(row.capital)}</td>
                    <td class="py-3 px-4 text-red-600">${divisa} ${formatNumber(row.interes)}</td>
                    <td class="py-3 px-4 font-semibold">${divisa} ${formatNumber(row.cuota)}</td>
                `;
                tablaBody.appendChild(tr);
            });

            cuotasJsonInput.value = JSON.stringify(cuotasData);

            // Mostrar el contenedor de resultados
            resultadosContainer.classList.remove('hidden');

            // Hacer scroll suave a los resultados
            resultadosContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        // Event listener para el botón calcular
        if (btnCalcular) {
            btnCalcular.addEventListener('click', calcular);
        }
    </script>
</x-layouts.app>
