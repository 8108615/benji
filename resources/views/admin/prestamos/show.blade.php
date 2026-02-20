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
                                <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
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
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-50 dark:bg-neutral-900 sticky top-0 z-10">
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500">
                                <th class="py-3 px-4">#</th>
                                <th class="py-3 px-4">Referencia</th>
                                <th class="py-3 px-4">Vencimiento</th>
                                <th class="py-3 px-4">Saldo capital</th>
                                <th class="py-3 px-4">Capital</th>
                                <th class="py-3 px-4">Interés</th>
                                <th class="py-3 px-4">Cuota</th>
                                <th class="py-3 px-4">Método</th>
                                <th class="py-3 px-4">Fecha cancelado</th>
                                <th class="py-3 px-4">Total pagado</th>
                                <th class="py-3 px-4">Estado de la cuota</th>
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
                                    <td>
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
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $estadoPagoClasses }}">
                                            {{ ucfirst($estadoPago) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($estadoPago === 'pendiente')
                                            <flux:modal.trigger name="show-pagos{{ $pago->id }}"
                                                variant="primary" data-open-modal>

                                                <flux:button variant="primary" class="cursor-pointer p-1"
                                                    color="green" icon="currency-dollar"
                                                    title="Ver detalles del pago">
                                                    Pagar</flux:button>
                                            </flux:modal.trigger>
                                            <flux:modal name="show-pagos{{ $pago->id }}" variant="primary"
                                                class="md:w-96">
                                                <div class="space-y-6">
                                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                                        <div class="flex items-center gap-3 mb-2">
                                                            <div
                                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                                <i class="fas fa-dollar-sign text-blue-600 dark:text-blue-400 text-lg"></i>
                                                            </div>
                                                            <div>
                                                                <flux:heading size="lg">Pagar
                                                                    {{ $pago->referencia_pago }}</flux:heading>
                                                                <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
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
                                                                <p class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                    Mora aplicada
                                                                </p>
                                                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                                    {{ $divisa }}
                                                                    {{ number_format($moraAplicada, 2) }}</p>
                                                            </div>
                                                        @endif
                                                        <div
                                                            class="p-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 mb-4">
                                                            <p class="text-xs uppercase text-rose-700 dark:text-rose-300">
                                                                Monto de la cuota
                                                            </p>
                                                            <p
                                                                class="text-base font-semibold text-gray-900 dark:text-white">
                                                                {{ $divisa }}
                                                                {{ number_format($pago->monto_cuota, 2) }}</p>
                                                        </div>
                                                        <flux:field>
                                                            <flux:label>Monto total pagado @if ($moraAplicada > 0)
                                                                    (incluye mora)
                                                                @endif
                                                            </flux:label>
                                                            <flux:input type="number"
                                                                value="{{ old('monto_total_pagado', number_format($montoTotalSugerido ?? 0, 2)) }}"
                                                                name="monto_total_pagado" step="0.01" required />
                                                            <flux:error name="monto_total_pagado" />
                                                        </flux:field>
                                                        <flux:button variant="primary" color="green"
                                                            class="w-full mt-4" type="submit">
                                                            Confirmar pago
                                                        </flux:button>
                                                    </form>
                                                </div>
                                            </flux:modal>
                                        @else
                                            <a href="{{ url('/admin/pago/' . $pago->id . '/comprobante') }}"
                                                class="inline-flex items-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded transition">
                                                <i class="fas fa-print mr-2"></i> Comprobante
                                            </a>
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
</x-layouts.app>
