<!DOCTYPE html>

<html lang="es">




<head>

    <meta charset="UTF-8">

    <title>Contrato de Préstamo #{{ $prestamo->id }}</title>

    <style>
        * {

            box-sizing: border-box;

        }




        body {

            font-family: DejaVu Sans, Arial, sans-serif;

            color: #1f2937;

            margin: 28px;

            font-size: 12px;

            line-height: 1.45;

        }




        .header {

            border-bottom: 2px solid #111827;

            padding-bottom: 12px;

            margin-bottom: 18px;

        }




        .company {

            font-size: 18px;

            font-weight: 700;

            color: #111827;

            margin-bottom: 4px;

        }




        .muted {

            color: #6b7280;

            font-size: 11px;

        }




        .title {

            margin-top: 14px;

            padding: 10px 12px;

            background: #f3f4f6;

            border: 1px solid #d1d5db;

            font-size: 15px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .4px;

        }




        .meta {

            margin-top: 10px;

            font-size: 11px;

            color: #374151;

        }




        .section {

            margin-top: 18px;

        }




        .section h3 {

            margin: 0 0 8px 0;

            font-size: 13px;

            color: #111827;

            border-left: 4px solid #4f46e5;

            padding-left: 8px;

        }




        .box {

            border: 1px solid #d1d5db;

            border-radius: 6px;

            padding: 10px 12px;

            background: #ffffff;

        }




        .grid-2 {

            width: 100%;

            border-collapse: separate;

            border-spacing: 10px 0;

            margin-left: -10px;

            margin-right: -10px;

        }




        .grid-2 td {

            width: 50%;

            vertical-align: top;

        }




        .row {

            margin: 2px 0;

        }




        .row .label {

            color: #6b7280;

            font-size: 11px;

            display: inline-block;

            min-width: 150px;

        }




        .amount-highlight {

            margin-top: 10px;

            padding: 10px;

            border: 1px dashed #9ca3af;

            background: #f9fafb;

            font-weight: 700;

        }




        table.schedule {

            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;

            font-size: 10.5px;

        }




        .schedule thead th {

            background: #eef2ff;

            color: #1e1b4b;

            border: 1px solid #c7d2fe;

            padding: 6px;

            text-align: center;

            font-weight: 700;

        }




        .schedule tbody td {

            border: 1px solid #d1d5db;

            padding: 5px 6px;

            text-align: right;

        }




        .schedule tbody td.left {

            text-align: left;

        }




        .schedule tfoot td {

            border: 1px solid #9ca3af;

            background: #f3f4f6;

            font-weight: 700;

            padding: 6px;

            text-align: right;

        }




        .clauses {

            margin-top: 12px;

            padding-left: 16px;

        }




        .clauses li {

            margin-bottom: 6px;

            text-align: justify;

        }




        .signatures {

            width: 100%;

            margin-top: 34px;

        }




        .signatures td {

            width: 50%;

            text-align: center;

            vertical-align: top;

            padding: 0 10px;

        }




        .line {

            margin-top: 42px;

            border-top: 1px solid #111827;

            padding-top: 6px;

            font-weight: 600;

            font-size: 11px;

        }




        .footer {

            margin-top: 14px;

            font-size: 10px;

            color: #6b7280;

            text-align: center;

        }




        .page-break {

            page-break-after: always;

        }
    </style>

</head>




<body>

    @php

        $divisa = $ajuste->divisa ?? '$';

        $fechaContrato = optional($prestamo->created_at)->format('d/m/Y');

    @endphp




    <div class="header">

        <div class="company">{{ $ajuste->nombre ?? 'Empresa de Préstamos' }}</div>

        <div class="muted">

            {{ $ajuste->direccion ?? '-' }}

            | Tel: {{ $ajuste->telefono ?? '-' }}

            | Email: {{ $ajuste->email ?? '-' }}

            @if (!empty($ajuste->web))
                | Web: {{ $ajuste->web }}
            @endif

        </div>

        <div class="title">Contrato de préstamo N° {{ $prestamo->id }}</div>

        <div class="meta">

            Fecha de emisión: {{ $fechaContrato ?: now()->format('d/m/Y') }}

        </div>

    </div>




    <table class="grid-2">

        <tr>

            <td>

                <div class="section">

                    <h3>Datos del cliente</h3>

                    <div class="box">

                        <div class="row"><span class="label">Nombre completo:</span>

                            {{ $cliente->apellidos }} {{ $cliente->nombres }}</div>

                        <div class="row"><span class="label">Documento:</span>

                            {{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</div>

                        <div class="row"><span class="label">Teléfono:</span> {{ $cliente->celular ?: '-' }}</div>

                        <div class="row"><span class="label">Dirección:</span> {{ $cliente->direccion ?: '-' }}

                        </div>

                    </div>

                </div>

            </td>

            <td>

                <div class="section">

                    <h3>Datos del préstamo</h3>

                    <div class="box">

                        <div class="row"><span class="label">Categoría:</span>

                            {{ optional($prestamo->categoria)->nombre ?: '-' }}</div>

                        <div class="row"><span class="label">Modalidad de pago:</span>

                            {{ $prestamo->modalidad_pago }}</div>

                        <div class="row"><span class="label">Amortización:</span>

                            {{ $prestamo->modalidad_amortizacion }}</div>

                        <div class="row"><span class="label">Fecha de inicio:</span>

                            {{ $prestamo->fecha_inicio ? \Carbon\Carbon::parse($prestamo->fecha_inicio)->format('d/m/Y') : '-' }}

                        </div>

                        <div class="row"><span class="label">Número de cuotas:</span> {{ $prestamo->nro_cuotas }}

                        </div>




                        <div class="amount-highlight">

                            Monto prestado: {{ $divisa }}

                            {{ number_format($prestamo->monto_prestado ?? 0, 2) }}<br>

                            Interés total: {{ $divisa }}

                            {{ number_format($prestamo->monto_interes_total ?? 0, 2) }}<br>

                            Total a pagar: {{ $divisa }}

                            {{ number_format($prestamo->monto_total_a_pagar ?? 0, 2) }}

                        </div>

                    </div>

                </div>

            </td>

        </tr>

    </table>




    <div class="section">

        <h3>Cláusulas principales</h3>

        <div class="box">

            <ol class="clauses">

                <li>La parte prestataria reconoce haber recibido el monto indicado y se compromete a devolverlo según el

                    cronograma de pagos detallado en este contrato.</li>

                <li>Los pagos se efectuarán conforme a la modalidad pactada, en las fechas de vencimiento señaladas en

                    la tabla de amortización.</li>

                <li>El incumplimiento de pago podrá generar mora diaria conforme a la política vigente de la entidad

                    acreedora.</li>

                <li>La parte prestataria declara que la información personal proporcionada es veraz y autoriza su

                    tratamiento para fines de gestión del crédito.</li>

                <li>Este documento constituye constancia formal de aceptación de las condiciones del préstamo.</li>

            </ol>

        </div>

    </div>




    <div class="section">

        <h3>Tabla de amortización</h3>

        <table class="schedule">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Referencia</th>

                    <th>Vencimiento</th>

                    <th>Saldo capital</th>

                    <th>Capital</th>

                    <th>Interés</th>

                    <th>Cuota</th>

                </tr>

            </thead>

            <tbody>

                @forelse ($pagos as $index => $pago)
                    <tr>

                        <td class="left">{{ $index + 1 }}</td>

                        <td class="left">{{ $pago->referencia_pago }}</td>

                        <td>{{ $pago->fecha_vencimiento ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') : '-' }}

                        </td>

                        <td>{{ $divisa }} {{ number_format($pago->saldo_capital ?? 0, 2) }}</td>

                        <td>{{ $divisa }} {{ number_format($pago->monto_capital ?? 0, 2) }}</td>

                        <td>{{ $divisa }} {{ number_format($pago->monto_interes ?? 0, 2) }}</td>

                        <td>{{ $divisa }} {{ number_format($pago->monto_cuota ?? 0, 2) }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="left">No existen cuotas registradas para este préstamo.</td>

                    </tr>
                @endforelse

            </tbody>

            <tfoot>

                <tr>

                    <td colspan="4" class="left">Totales</td>

                    <td>{{ $divisa }} {{ number_format($totalCapital ?? 0, 2) }}</td>

                    <td>{{ $divisa }} {{ number_format($totalInteres ?? 0, 2) }}</td>

                    <td>{{ $divisa }} {{ number_format($totalCuotas ?? 0, 2) }}</td>

                </tr>

            </tfoot>

        </table>

    </div>




    <table class="signatures">

        <tr>

            <td>

                <div class="line">Firma y sello de la empresa</div>

            </td>

            <td>

                <div class="line">Firma del cliente</div>

            </td>

        </tr>

    </table>




    <div class="footer">

        Documento generado por el sistema el {{ now()->format('d/m/Y H:i') }}.

    </div>

</body>




</html>
