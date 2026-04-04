<x-layouts.app title="Detalle del Préstamo">
    @php
        $divisa = $ajuste->divisa ?? '$';
        $cliente = $prestamo->cliente;
        $categoria = $prestamo->categoria;
        $pagos = $prestamo->pagos ?? collect();
        $totalPagado = $pagos->sum('monto_total_pagado');
        $saldoPendiente = max(($prestamo->monto_total_a_pagar ?? 0) - $totalPagado, 0);
        $cuotasPagadas = $pagos->where('estado', 'pagado')->count();
        $cuotasPendientes = $pagos->where('estado', 'pendiente')->count();
        $totalCuotas = max($prestamo->nro_cuotas ?? 0, 1);
        $porcentajePagado = min(($totalPagado / max($prestamo->monto_total_a_pagar ?? 0, 1)) * 100, 100);
        $hoy = \Carbon\Carbon::today();
        $estado = strtolower($prestamo->estado ?? 'pendiente');
        $estadoClasses = match ($estado) {
            'pagado' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
            'cancelado' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
            default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        };
    @endphp
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-7xl">
            <div class="relative mb-6 w-full">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <flux:heading size="xl" level="1">Detalle del Préstamo</flux:heading>
                        <p class="text-slate-500 dark:text-neutral-400">Información completa del préstamo y sus pagos.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ url('/admin/prestamos') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="mt-4">
                    <flux:separator variant="subtle" />
                </div>
            </div>
            <div class="flex flex-nowrap gap-4 mb-6 overflow-x-auto pb-2 -mx-2 px-2">
                <div
                    class="min-w-[260px] flex-1 p-7 rounded-2xl border border-slate-200/70 dark:border-slate-700/60 bg-gradient-to-br from-white to-slate-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 px-4">Monto prestado</p>
                        <span
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                            <i class="fas fa-hand-holding-usd"></i>
                        </span>
                    </div>
                    <p class="mt-3 text-2xl font-semibold text-gray-900 dark:text-white px-4">{{ $divisa }}
                        {{ number_format($prestamo->monto_prestado ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 px-4">Tasa: {{ number_format($prestamo->tasa_interes ?? 0, 2) }}%
                    </p>
                </div>
                <div
                    class="min-w-[260px] flex-1 p-7 rounded-2xl border border-slate-200/70 dark:border-slate-700/60 bg-gradient-to-br from-white to-slate-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 px-4">Interés total</p>
                        <span
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-300">
                            <i class="fas fa-percentage"></i>
                        </span>
                    </div>
                    <p class="mt-3 text-2xl font-semibold text-gray-900 dark:text-white px-4">{{ $divisa }}
                        {{ number_format($prestamo->monto_interes_total ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 px-4">Modalidad: {{ $prestamo->modalidad_amortizacion }}</p>
                </div>
                <div
                    class="min-w-[260px] flex-1 p-7 rounded-2xl border border-slate-200/70 dark:border-slate-700/60 bg-gradient-to-br from-white to-slate-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 px-4">Total a pagar</p>
                        <span
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300">
                            <i class="fas fa-coins"></i>
                        </span>
                    </div>
                    <p class="mt-3 text-2xl font-semibold text-gray-900 dark:text-white px-4">{{ $divisa }}
                        {{ number_format($prestamo->monto_total_a_pagar ?? 0, 2) }}</p>
                    <p class="text-xs text-gray-400 px-4">Cuotas: {{ $prestamo->nro_cuotas }}</p>
                </div>
                <div
                    class="min-w-[260px] flex-1 p-7 rounded-2xl border border-slate-200/70 dark:border-slate-700/60 bg-gradient-to-br from-white to-slate-50 dark:from-neutral-900 dark:to-neutral-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 px-4">Estado del préstamo</p>
                        <span
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 text-violet-600 dark:bg-violet-900/40 dark:text-violet-300">
                            <i class="fas fa-flag"></i>
                        </span>
                    </div>
                    <div class="mt-3 px-4">
                        <span
                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $estadoClasses }}">
                            {{ ucfirst($estado) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 px-4">Inicio:
                        {{ $prestamo->fecha_inicio ? \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') : '-' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
                <div
                    class="xl:col-span-2 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <flux:heading level="2" size="lg" class="text-blue-600">Datos del préstamo
                        </flux:heading>
                        <p class="text-sm text-gray-500">Detalle completo del contrato.</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs uppercase text-gray-400">Modalidad de pago</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $prestamo->modalidad_pago }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Modalidad de amortización</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $prestamo->modalidad_amortizacion }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Número de cuotas</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $prestamo->nro_cuotas }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Fecha de inicio</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $prestamo->fecha_inicio ? \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Monto prestado</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $divisa }}
                                {{ number_format($prestamo->monto_prestado ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Tasa de interés</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($prestamo->tasa_interes ?? 0, 2) }}%</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Interés total</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $divisa }}
                                {{ number_format($prestamo->monto_interes_total ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Total a pagar</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $divisa }}
                                {{ number_format($prestamo->monto_total_a_pagar ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Creado</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ optional($prestamo->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-gray-400">Actualizado</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ optional($prestamo->updated_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <flux:heading level="2" size="lg" class="text-blue-600">Cliente y categoría
                            </flux:heading>
                            <p class="text-sm text-gray-500">Información asociada.</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div
                                    class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border border-slate-100 dark:border-neutral-700">
                                    <p class="text-xs uppercase text-gray-400">Cliente</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $cliente->nombres }} {{ $cliente->apellidos }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $cliente->tipo_documento }}:
                                        {{ $cliente->numero_documento }}</p>
                                </div>
                                <div
                                    class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border border-slate-100 dark:border-neutral-700">
                                    <p class="text-xs uppercase text-gray-400">Contacto</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $cliente->celular }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $cliente->direccion }}</p>
                                </div>
                                <div
                                    class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border border-slate-100 dark:border-neutral-700">
                                    <p class="text-xs uppercase text-gray-400">Categoría</p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $categoria->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <flux:heading level="2" size="lg" class="text-blue-600">Resumen de pagos
                                </flux:heading>
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 px-2.5 py-0.5 text-xs font-semibold">
                                    {{ number_format($porcentajePagado, 2) }}%
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                                <div
                                    class="rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-3 py-2">
                                    <p class="text-[11px] uppercase text-emerald-700 dark:text-emerald-300">Total
                                        pagado</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $divisa }}
                                        {{ number_format($totalPagado, 2) }}</p>
                                </div>
                                <div
                                    class="rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 px-3 py-2">
                                    <p class="text-[11px] uppercase text-amber-700 dark:text-amber-300">Saldo pendiente
                                    </p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $divisa }}
                                        {{ number_format($saldoPendiente, 2) }}</p>
                                </div>
                                <div
                                    class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800">
                                    <p class="text-xs uppercase text-emerald-600">Cuotas pagadas</p>
                                    <p class="text-lg font-semibold text-emerald-700 dark:text-emerald-300">
                                        {{ $cuotasPagadas }}
                                    </p>
                                </div>
                                <div
                                    class="p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                                    <p class="text-xs uppercase text-amber-600">Cuotas pendientes</p>
                                    <p class="text-lg font-semibold text-amber-700 dark:text-amber-300">
                                        {{ $cuotasPendientes }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <small>{{ number_format($porcentajePagado, 2) }}% pagado del préstamo</small>
                                <div class="h-2.5 w-full rounded-full bg-gray-100 dark:bg-neutral-700 overflow-hidden">
                                    <div class="h-full bg-emerald-500"
                                        style="width: {{ number_format($porcentajePagado, 2) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading level="2" size="lg" class="text-blue-600">Pagos del préstamo -
                                Tabla de amortización
                            </flux:heading>
                            <p class="text-sm text-gray-500">Registro completo de todas las cuotas.</p>
                        </div>
                        <div class="text-xs text-gray-400">{{ $pagos->count() }} pagos</div>
                        @if (($liquidacion['total_liquidacion'] ?? 0) > 0 && strtolower($prestamo->estado ?? 'pendiente') === 'pendiente')
                            <flux:modal.trigger name="liquidar-prestamo" variant="danger" data-open-modal>
                                <flux:button variant="danger" class="cursor-pointer p-1" color="red"
                                    title="Liquidar préstamo">
                                    <i class="fas fa-hand-holding-usd mr-2"></i>
                                    Liquidar Préstamo
                                </flux:button>
                            </flux:modal.trigger>
                        @endif
                    </div>
                </div>

                <flux:modal name="liquidar-prestamo" variant="danger" class="md:w-96">
                    <div class="space-y-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                    <i class="fas fa-hand-holding-usd text-red-600 dark:text-red-400 text-lg"></i>
                                </div>
                                <div>
                                    <flux:heading size="lg">Liquidar Préstamo</flux:heading>
                                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Al liquidar el préstamo se cancela todas las cuotas Pendientes.
                                    </flux:text>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <div
                                class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                <p class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                    Capital Restante</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $divisa }}
                                    {{ number_format($liquidacion['capital_restante'] ?? 0, 2) }}</p>
                            </div>
                            <div
                                class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border border-slate-200 dark:border-slate-700">
                                <p class="text-xs uppercase text-gray-400">Mora Devengada</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $divisa }}
                                    {{ number_format($liquidacion['mora_devengada'] ?? 0, 2) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    ({{ $liquidacion['dias_mora'] ?? 0 }} Dias de mora despues de
                                    {{ $liquidacion['dias_gracia'] ?? 0 }} Dias de Gracia)
                                </p>
                            </div>

                            <div
                                class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border border-slate-200 dark:border-slate-700">
                                <p class="text-xs uppercase text-gray-400">Interés Devengada</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $divisa }}
                                    {{ number_format($liquidacion['interes_devengada'] ?? 0, 2) }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    (Basado en {{ $liquidacion['dias_devengados'] ?? 0 }} Dias del Periodo Actual)
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Cálculo de interés: Cuota actual x (días transcurrido / dias del periodo).
                                </p>
                            </div>
                            <div
                                class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800">
                                <p class="text-xs uppercase text-rose-700 dark:text-rose-300"> Total a pagar</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white"> {{ $divisa }}
                                    {{ number_format($liquidacion['total_liquidacion'] ?? 0, 2) }} </p>
                            </div>

                            <form action="{{ route('admin.prestamos.liquidar', $prestamo->id) }}" method="POST">
                                @csrf
                                <flux:button variant="primary" color="red" class="w-full mt-4" type="submit">
                                Liquidar Préstamo
                            </flux:button>
                            </form>
                        </div>

                    </div>
                </flux:modal>

                <div id="pagos-scroll-top" class="overflow-x-auto border-b border-gray-100 dark:border-gray-700">
                    <div id="pagos-scroll-top-content" style="height: 1px; width: 1850px;"></div>
                </div>

                <div class="overflow-x-auto" id="pagos-scroll-bottom">
                    <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-50 dark:bg-neutral-900 sticky top-0 z-10">
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500">
                                <th class="py-3 px-4">Nro</th>
                                <th class="py-3 px-4">Referencia</th>
                                <th class="py-3 px-4">Vencimiento</th>
                                <th class="py-3 px-4">Saldo capital</th>
                                <th class="py-3 px-4">Capital</th>
                                <th class="py-3 px-4">Interés</th>
                                <th class="py-3 px-4">Cuota</th>
                                <th class="py-3 px-4">Método</th>
                                <th class="py-3 px-4">Fecha cancelado</th>
                                <th class="py-3 px-4">Total pagado</th>
                                <th class="py-3 px-4" style="min-width: 200px; width: 200px;">Estado de la cuota</th>
                                <th class="py-3 px-4">Pagos Parciales</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-neutral-700">
                            @forelse ($pagos as $index => $pago)
                                @php
                                    $estadoPago = strtolower($pago->estado ?? 'pendiente');
                                    $estadoPagoClasses = match ($estadoPago) {
                                        'pagado'
                                            => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        'cancelado'
                                            => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                                        default
                                            => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    };
                                    $vencido =
                                        $estadoPago === 'pendiente' &&
                                        $pago->fecha_vencimiento &&
                                        \Carbon\Carbon::parse($pago->fecha_vencimiento)->lt($hoy);
                                    $total_pago_parcial = 0;
                                    $total_pago_parcial = $pago->pagosParciales->sum('monto_pagado');
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/40"
                                    style="background-color: {{ $estadoPago === 'pagado' ? '#dcfce7' : ($vencido ? '#ffe4e6' : ($loop->odd ? '#f3f4f6' : '#ffffff')) }};">
                                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                                    <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                        {{ $pago->referencia_pago }}
                                    </td>
                                    <td class="py-3 px-4">
                                        {{ $pago->fecha_vencimiento ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-4">{{ $divisa }}
                                        {{ number_format($pago->saldo_capital ?? 0, 2) }}</td>
                                    <td class="py-3 px-4">{{ $divisa }}
                                        {{ number_format($pago->monto_capital ?? 0, 2) }}</td>
                                    <td class="py-3 px-4">{{ $divisa }}
                                        {{ number_format($pago->monto_interes ?? 0, 2) }}</td>
                                    <td class="py-3 px-4">{{ $divisa }}
                                        {{ number_format($pago->monto_cuota ?? 0, 2) }}
                                    </td>
                                    <td class="py-3 px-4">{{ $pago->metodo_pago }}</td>
                                    <td class="py-3 px-4">
                                        {{ $pago->fecha_cancelado ? \Carbon\Carbon::parse($pago->fecha_cancelado)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-4">{{ $divisa }}
                                        {{ number_format($pago->monto_total_pagado ?? 0, 2) }}</td>
                                    <td class="py-3 px-4" style="min-width: 200px; width: 200px;">
                                        @php
                                            $diasGracia = $ajuste->dias_gracia ?? 0;
                                            $tasaMoraDiaria = ($ajuste->mora ?? 0) / 100;
                                            $diasNotificacion = $ajuste->dias_notificacion ?? 0;
                                            $fechaActual = \Carbon\Carbon::now()->startOfDay();
                                            $fechaVencimiento = $pago->fecha_vencimiento
                                                ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->startOfDay()
                                                : null;
                                            $diasAtraso = 0;
                                            $diasParaVencer = 0;
                                            $diasEnGracia = 0;
                                            $diasDesdeVencimiento = 0;
                                            $estaAtrasado = false;
                                            $proximoAVencer = false;
                                            $enPeriodoGracia = false;
                                            $montoMora = 0;
                                            $diasRestantesGracia = 0;
                                            $diaOrdinalGracia = '';
                                            if ($pago->estado === 'pendiente' && $fechaVencimiento) {
                                                if ($fechaVencimiento < $fechaActual) {
                                                    // Está vencido
                                                    $diasDesdeVencimiento = $fechaVencimiento->diffInDays($fechaActual);
                                                    // Verificar si está en período de gracia
                                                    if ($diasDesdeVencimiento <= $diasGracia) {
                                                        $enPeriodoGracia = true;
                                                        $diasRestantesGracia = max(
                                                            $diasGracia - $diasDesdeVencimiento,
                                                            0,
                                                        );
                                                        $diaOrdinalGracia =
                                                            $diasDesdeVencimiento === 1
                                                                ? '1er'
                                                                : ($diasDesdeVencimiento === 2
                                                                    ? '2do'
                                                                    : ($diasDesdeVencimiento === 3
                                                                        ? '3er'
                                                                        : $diasDesdeVencimiento . 'º'));
                                                    } else {
                                                        $estaAtrasado = true;
                                                        $diasAtraso = $diasDesdeVencimiento - $diasGracia;
                                                        $montoMora = $pago->monto_cuota * $tasaMoraDiaria * $diasAtraso;
                                                    }
                                                } else {
                                                    $diasParaVencer = $fechaActual->diffInDays($fechaVencimiento);
                                                    if ($diasParaVencer <= $diasNotificacion) {
                                                        $proximoAVencer = true;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div class="flex flex-col gap-1 text-xs">
                                            @if (!$fechaVencimiento)
                                                <span class="text-gray-500">Sin vencimiento</span>
                                            @elseif ($pago->estado !== 'pendiente')
                                                <span class="text-emerald-600">Sin pendientes</span>
                                            @elseif ($enPeriodoGracia)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    EN PERIODO DE GRACIA
                                                </span>
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-calendar-times"></i>
                                                    Vencido hace: {{ $diasDesdeVencimiento }} días
                                                </span>
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-flag"></i>
                                                    {{ $diaOrdinalGracia }} día de gracia
                                                </span>
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-teal-100 text-teal-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-shield-alt"></i>
                                                    Sin mora aún
                                                </span>
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-indigo-100 text-indigo-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    Quedan {{ $diasRestantesGracia }} días de gracia
                                                </span>
                                            @elseif ($estaAtrasado)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-rose-100 text-rose-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Atraso: {{ $diasAtraso }} día(s)
                                                </span>
                                                @if ($montoMora > 0)
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-rose-200 text-rose-800 px-2 py-0.5 text-[11px] font-semibold">
                                                        <i class="fas fa-coins"></i>
                                                        Mora: {{ $divisa }} {{ number_format($montoMora, 2) }}
                                                    </span>
                                                @endif
                                            @else
                                                @if ($diasParaVencer === 0)
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-[11px] font-semibold">
                                                        <i class="fas fa-calendar-day"></i>
                                                        Vence hoy
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-[11px] font-semibold">
                                                        <i class="fas fa-calendar"></i>
                                                        Faltan {{ $diasParaVencer }} día(s)
                                                    </span>
                                                @endif
                                                @if ($proximoAVencer)
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-[11px] font-semibold">
                                                        <i class="fas fa-bell"></i>
                                                        Por vencer
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-1 text-xs min-w-[220px]">
                                            @if ($pago->pagosParciales->isNotEmpty())
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-blue-100 text-blue-700 px-2 py-0.5 text-[11px] font-semibold">
                                                    {{ $pago->pagosParciales->count() }} pago(s) parcial(es)
                                                </span>
                                                <hr>
                                                @php
                                                    $total_pago_parcial = 0;

                                                    $moraAplicada = $montoMora ?? 0;
                                                    $montoBaseCuota = $pago->monto_cuota ?? 0;
                                                    $montoTotalSugerido = $montoBaseCuota + $moraAplicada;
                                                    $montoTotalPagadoOld = old('monto_total_pagado');
                                                    $montoTotalPagadoValue =
                                                        $montoTotalPagadoOld !== null
                                                            ? str_replace(
                                                                ',',
                                                                '.',
                                                                preg_replace(
                                                                    '/[^\d.,-]/',
                                                                    '',
                                                                    (string) $montoTotalPagadoOld,
                                                                ),
                                                            )
                                                            : number_format(
                                                                (float) ($montoTotalSugerido ?? 0),
                                                                2,
                                                                '.',
                                                                '',
                                                            );
                                                @endphp
                                                @foreach ($pago->pagosParciales as $pagoParcial)
                                                    <span
                                                        class="inline-flex items-center flex-nowrap whitespace-nowrap gap-1 rounded-full bg-blue-50 text-blue-600 px-2 py-0.5 text-[11px] font-medium">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                        {{ $divisa }}
                                                        {{ number_format($pagoParcial->monto_pagado ?? 0, 2) }}
                                                        -
                                                        {{ $pagoParcial->fecha_pago ? \Carbon\Carbon::parse($pagoParcial->fecha_pago)->format('d/m/Y') : '-' }}
                                                        <form
                                                            action="{{ url('/admin/pago_parcial/' . $pagoParcial->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Confirma que desea Eliminar este pago parcial?');"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="text"
                                                                value="{{ $montoTotalPagadoValue }}"
                                                                name="monto_total_pagado" hidden>
                                                            <flux:button type="submit" style="cursor: pointer"
                                                                class="text-red-500 hover:text-red-700 ml-2">
                                                                <i class="fas fa-trash"></i>
                                                            </flux:button>
                                                        </form>
                                                    </span>
                                                    @php
                                                        $total_pago_parcial += $pagoParcial->monto_pagado ?? 0;
                                                    @endphp
                                                @endforeach
                                                <hr>
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-blue-200 text-blue-800 px-2 py-0.5 text-[11px] font-semibold">
                                                    {{ $divisa }} {{ number_format($total_pago_parcial, 2) }}
                                                    Total pagado
                                                </span>
                                            @endif

                                        </div>

                                    </td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $estadoPagoClasses }}">
                                            {{ ucfirst($estadoPago) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($estadoPago === 'pendiente')
                                            <flux:button.group>
                                                @if (!$pago->pagosParciales->isNotEmpty())
                                                    <flux:modal.trigger name="show-pagos{{ $pago->id }}"
                                                        variant="primary" data-open-modal>
                                                        <flux:button variant="primary" class="cursor-pointer p-1"
                                                            color="green" icon="currency-dollar"
                                                            title="Ver detalles del pago">
                                                            Pagar</flux:button>
                                                    </flux:modal.trigger>
                                                @endif

                                                <flux:modal.trigger name="show-pagos_parciales{{ $pago->id }}"
                                                    variant="primary" data-open-modal>
                                                    <flux:button variant="primary" class="cursor-pointer p-1"
                                                        color="blue" icon="banknotes"
                                                        title="Registrar pago parcial">
                                                        Pago Parcial</flux:button>
                                                </flux:modal.trigger>

                                            </flux:button.group>

                                            <flux:modal name="show-pagos{{ $pago->id }}" variant="primary"
                                                class="md:w-96">
                                                <div class="space-y-6">
                                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                        <div class="flex items-center gap-3 mb-2">
                                                            <div
                                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                                <i
                                                                    class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <flux:heading size="lg">Pagar
                                                                    {{ $pago->referencia_pago }}</flux:heading>
                                                                <flux:text
                                                                    class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                                    Asegúrese de ingresar la información correcta antes
                                                                    de confirmar el pago.
                                                                </flux:text>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $moraAplicada = $montoMora ?? 0;
                                                        $montoBaseCuota = $pago->monto_cuota ?? 0;
                                                        $montoTotalSugerido = $montoBaseCuota + $moraAplicada;
                                                        $montoTotalPagadoOld = old('monto_total_pagado');
                                                        $montoTotalPagadoValue =
                                                            $montoTotalPagadoOld !== null
                                                                ? str_replace(
                                                                    ',',
                                                                    '.',
                                                                    preg_replace(
                                                                        '/[^\d.,-]/',
                                                                        '',
                                                                        (string) $montoTotalPagadoOld,
                                                                    ),
                                                                )
                                                                : number_format(
                                                                    (float) ($montoTotalSugerido ?? 0),
                                                                    2,
                                                                    '.',
                                                                    '',
                                                                );
                                                    @endphp
                                                    <form action="{{ url('/admin/pago/create') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="pago_id"
                                                            value="{{ $pago->id }}" />
                                                        <flux:separator variant="subtle" />
                                                        <br><br>
                                                        <label for="">Método de Pago</label>
                                                        <flux:select wire:model="metodo_pago" name="metodo_pago"
                                                            placeholder="Seleccione un método de pago..." required>
                                                            <flux:select.option>Efectivo</flux:select.option>
                                                            <flux:select.option>Tarjeta de crédito/débito
                                                            </flux:select.option>
                                                            <flux:select.option>Transferencia bancaria
                                                            </flux:select.option>
                                                            <flux:select.option>Otro</flux:select.option>
                                                        </flux:select>
                                                        <br>
                                                        <flux:field>
                                                            <flux:label>Fecha de cancelación</flux:label>
                                                            <flux:input type="date" name="fecha_cancelacion"
                                                                value="{{ old('fecha_cancelacion', now()->toDateString()) }}"
                                                                required />
                                                            <flux:error name="fecha_cancelacion" />
                                                        </flux:field>
                                                        <br>
                                                        @if ($moraAplicada > 0)
                                                            <div
                                                                class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                                <p
                                                                    class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                    Mora aplicada
                                                                <p
                                                                    class="text-base font-semibold text-gray-900 dark:text-white">
                                                                    {{ $divisa }}
                                                                    {{ number_format($moraAplicada, 2) }}</p>
                                                            </div>
                                                        @endif
                                                        <div
                                                            class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                            <p
                                                                class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                Monto de la cuota</p>
                                                            <p
                                                                class="text-base font-semibold text-gray-900 dark:text-white">
                                                                {{ $divisa }}
                                                                {{ number_format($pago->monto_cuota, 2) }}</p>
                                                        </div>

                                                        <div
                                                            class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                            <flux:field>
                                                                <flux:label
                                                                    class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                    MONTO TOTAL PAGADO @if ($moraAplicada > 0)
                                                                        (incluye mora)
                                                                    @endif
                                                                </flux:label>

                                                                <p
                                                                    class="text-base font-semibold text-gray-900 dark:text-white">
                                                                    {{ $divisa }}
                                                                    {{ number_format($montoTotalPagadoValue, 2) }}
                                                                </p>


                                                            </flux:field>
                                                        </div>
                                                        <flux:input type="hidden"
                                                            value="{{ $montoTotalPagadoValue }}"
                                                            name="monto_total_pagado" step="0.01" required />
                                                        <flux:error name="monto_total_pagado" />



                                                        <flux:button variant="primary" color="green"
                                                            class="w-full mt-4" type="submit">
                                                            Confirmar pago
                                                        </flux:button>
                                                    </form>
                                                </div>
                                            </flux:modal>

                                            <flux:modal name="show-pagos_parciales{{ $pago->id }}"
                                                variant="primary" class="md:w-96">
                                                <div class="space-y-6">
                                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                        <div class="flex items-center gap-3 mb-2">
                                                            <div
                                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                                <i
                                                                    class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <flux:heading size="lg">Pago Parcial de la Cuota
                                                                    {{ $pago->referencia_pago }}</flux:heading>
                                                                <flux:text
                                                                    class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                                    Registra un abono parcial para esta cuota. El monto
                                                                    total
                                                                    pagago se actualizara automaticamente al confirmar
                                                                    el pago parcial
                                                                </flux:text>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="p-4 rounded-lg bg-slate-50 dark:bg-neutral-900/40 border-slate-200 dark:border-slate-700">
                                                        @if ($pago->pagosParciales->isNotEmpty())
                                                            <div class="flex items-center justify-between mb-2">
                                                                <p class="text-xs font-semibold text-blue-700"> Pagos
                                                                    Parciales</p>
                                                                <span
                                                                    class="inline-flex items-center round-full bg-blue-100 px-2 py-0.5 font-semibold text-blue-700">
                                                                    {{ $pago->pagosParciales->count() }} registrado(s)
                                                                </span>
                                                            </div>



                                                            @foreach ($pago->pagosParciales as $pagoParcial)
                                                                <span class="text-xs">
                                                                    <i class="fas fa-hand-holding-usd"></i>
                                                                    {{ $divisa }}
                                                                    {{ number_format($pagoParcial->monto_pagado ?? 0, 2) }}
                                                                    -
                                                                    {{ $pagoParcial->fecha_pago ? \Carbon\Carbon::parse($pagoParcial->fecha_pago)->format('d/m/Y') : '-' }}
                                                                </span>
                                                                <br>
                                                            @endforeach
                                                            <hr>
                                                            <span
                                                                class="inline-flex items-center gap-1 rounded-full bg-blue-200 text-blue-800 px-2 py-0.5 text-[11px] font-semibold">
                                                                {{ $divisa }}
                                                                {{ number_format($total_pago_parcial, 2) }} Total
                                                                pagado
                                                            </span>
                                                        @else
                                                            <div class="flex items-center justify-between mb-2">
                                                                <p class="text-xs font-semibold text-blue-700">Sin
                                                                    pagos
                                                                    Parciales</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if ($moraAplicada > 0)
                                                        <div
                                                            class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                            <p
                                                                class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                Mora aplicada
                                                            <p
                                                                class="text-base font-semibold text-gray-900 dark:text-white">
                                                                {{ $divisa }}
                                                                {{ number_format($moraAplicada, 2) }}</p>
                                                        </div>
                                                    @endif
                                                    <div
                                                        class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                        <p class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                            Monto de la cuota</p>
                                                        <p
                                                            class="text-base font-semibold text-gray-900 dark:text-white">
                                                            {{ $divisa }}
                                                            {{ number_format($pago->monto_cuota, 2) }}</p>
                                                    </div>
                                                    <div
                                                        class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                        <flux:field>
                                                            <flux:label
                                                                class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                MONTO TOTAL A PAGAR @if ($moraAplicada > 0)
                                                                    (incluye mora)
                                                                @endif
                                                            </flux:label>
                                                            <p
                                                                class="text-base font-semibold text-gray-900 dark:text-white">
                                                                {{ $divisa }}
                                                                {{ number_format($montoTotalPagadoValue, 2) }}
                                                            </p>
                                                        </flux:field>
                                                    </div>
                                                    @php
                                                        $saldo_de_la_cuota =
                                                            $montoTotalPagadoValue - ($total_pago_parcial ?? 0);
                                                    @endphp
                                                    <form action="{{ url('/admin/pago_parcial/create') }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="pago_id"
                                                            value="{{ $pago->id }}" />
                                                        <input type="hidden" name="monto_total_de_la_cuota"
                                                            value="{{ $montoTotalPagadoValue }}" required />


                                                        <flux:field>
                                                            <flux:label>Monto Pagado (Parcial)</flux:label>
                                                            <flux:input type="number" step="0.01" min="0.01"
                                                                max="{{ number_format(max($saldo_de_la_cuota, 0), 2, '.', '') }}"
                                                                name="monto_pagado"
                                                                value="{{ old('monto_pagado', $saldo_de_la_cuota) }}"
                                                                required />
                                                            <flux:error name="monto_pagado" />
                                                        </flux:field>

                                                        <br>
                                                        <flux:field>
                                                            <flux:label>Fecha de pago</flux:label>
                                                            <flux:input type="date" name="fecha_pago"
                                                                value="{{ old('fecha_pago', now()->toDateString()) }}"
                                                                required />
                                                            <flux:error name="fecha_pago" />
                                                        </flux:field>

                                                        <br>

                                                        <flux:field>
                                                            <flux:label>Observacion (Opcional)</flux:label>
                                                            <flux:input type="text" name="detalle_pago"
                                                                value="{{ old('detalle_pago') }}" />
                                                            <flux:error name="detalle_pago" />
                                                        </flux:field>


                                                        <flux:button variant="primary" color="green"
                                                            class="w-full mt-4" type="submit">
                                                            Confirmar pago Parcial
                                                        </flux:button>
                                                    </form>
                                                </div>
                                            </flux:modal>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <flux:button.group>
                                                    <flux:modal.trigger name="show-comprobante{{ $pago->id }}"
                                                        variant="primary" data-open-modal>
                                                        <flux:button variant="primary" class="cursor-pointer p-1"
                                                            color="yellow" icon="printer"
                                                            title="Ver Cmprobante del pago">
                                                            Ver Comprobante</flux:button>
                                                    </flux:modal.trigger>

                                                    <flux:modal name="show-comprobante{{ $pago->id }}"
                                                        variant="primary" class="w-full max-w-6xl">
                                                        <div class="space-y-6">
                                                            <div
                                                                class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                                <div class="flex items-center gap-3 mb-2">
                                                                    <div
                                                                        class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                                        <i
                                                                            class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-lg"></i>
                                                                    </div>
                                                                    <div>
                                                                        <flux:heading size="lg">Comprobante de
                                                                            Pago
                                                                            {{ $pago->referencia_pago }}
                                                                        </flux:heading>
                                                                    </div>
                                                                </div>
                                                                <div class="w-full">
                                                                    <iframe
                                                                        src="{{ url('/admin/pago/' . $pago->id . '/comprobante') }}"
                                                                        frameborder="0" class="w-full h-96"
                                                                        style="height: 650px"></iframe>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </flux:modal>

                                                    <flux:modal.trigger name="delete-pago{{ $pago->id }}"
                                                        variant="danger">
                                                        <flux:button variant="danger" class="cursor-pointer"
                                                            style="border-radius: 0px 7px 7px 0px"><i
                                                                class="fas fa-trash-alt"></i>
                                                            Borrar
                                                        </flux:button>
                                                    </flux:modal.trigger>

                                                    <flux:modal name="delete-pago{{ $pago->id }}"
                                                        class="min-w-[22rem]">
                                                        <form
                                                            action="{{ url('/admin/pago/' . $pago->id . '/borrar') }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="space-y-6">
                                                                <div>
                                                                    <flux:heading size="lg">Borrar pago
                                                                    </flux:heading>

                                                                    <flux:text class="mt-2">
                                                                        Estás a punto de borrar este pago.<br>
                                                                        Esta acción no se puede deshacer.
                                                                    </flux:text>
                                                                </div>

                                                                <div class="flex gap-2">
                                                                    <flux:spacer />

                                                                    <flux:modal.close>
                                                                        <flux:button variant="ghost">Cancelar
                                                                        </flux:button>
                                                                    </flux:modal.close>

                                                                    <flux:button type="submit" variant="danger">
                                                                        Borrar
                                                                        Pago
                                                                    </flux:button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </flux:modal>
                                                </flux:button.group>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-6 px-4 text-center text-gray-500" colspan="12">No hay pagos
                                        registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topScroll = document.getElementById('pagos-scroll-top');
            const topContent = document.getElementById('pagos-scroll-top-content');
            const bottomScroll = document.getElementById('pagos-scroll-bottom');
            const table = bottomScroll ? bottomScroll.querySelector('table') : null;

            if (!topScroll || !topContent || !bottomScroll || !table) {
                return;
            }
            const syncScroll = () => {
                topContent.style.width = `${$table.scrollWidth}px`;
            };
            let syncing = false;

            topScroll.addEventListener('scroll', function() {
                if (syncing) return;
                syncing = true;
                bottomScroll.scrollLeft = topScroll.scrollLeft;
                syncing = false;
            });
            bottomScroll.addEventListener('scroll', function() {
                if (syncing) return;
                syncing = true;
                topScroll.scrollLeft = bottomScroll.scrollLeft;
                syncing = false;
            });

            syncTopWidth();
            window.addEventListener('resize', syncTopWidth);

        });
    </script>

</x-layouts.app>
