<html>
    <head>
        <meta charset="UTF-8">
        <title>Comprobante #{{ $pago->id }} </title>
        <style>
            /*reset y configuacion base */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                width: 80mm;
                min-height: 100mm;
                font-family: 'Arial Narrow', sans-serif;
                font-size: 9px;
                padding: 2mm;
                margin: 0 auto;
            }

            /*Encabezado */
            .header {
                text-align: center;
                margin-bottom: 2mm;
                padding-bottom: 2mm;
                border-bottom: 1px dashed #000;
            }

            .logo {
                max-width: 50mm;
                max-height: 15mm;
                margin: 0 auto 1mm;
            }
            .company-name {
                font-weight: bold;
                font-size: 10px;
                margin-bottom: 1mm;
            }

            /*Datos de la factura */
            .invoice-info {
                text-align: center;
                margin: 2mm 0;
            }

            .invoice-number {
                font-weight: bold;
                font-size: 11px;
            }

            /* Totales */
            .totals {
                margin: 3mm 0;
                font-size: 9px;
            }

            .total-row {
                font-weight: bold;
                border-top: 1px solid #000;
                border-bottom: 2px double #000;
            }

            /* Pie de pagina*/
            .footer {
                margin-top: 3mm;
                padding-top: 2mm;
                border-top: 1px dashed #000;
                text-align: center;
                font-size: 8px;
            }
        </style>
    </head>
    <body>
        <!-- encabezado -->
        <div class="header">
            <br>
            {{ $ajuste->nombre }}<br>
            {{ $ajuste->direccion }}<br>
            {{ $ajuste->telefono }}<br>
            {{ $ajuste->email }}<br>
            <br>
        </div>

        <!-- Informacion de factura -->
        <div class="invoice-info">
            <div class="invoice-number">COMPROBANTE DE PAGO # {{ $numero_pago }}</div>
        </div>
        <br>

        <!-- Datos del Cliente -->
        <div style="text-align: left;">
            <strong>DATOS DEL CLIENTE</strong><br><br>
            <b>Documento: </b> {{ $cliente->tipo_documento . '  - ' . $cliente->numero_documento }}<br>
            <b>Señor(es): </b> {{ $cliente->apellidos . ' '. $cliente->nombres }}<br>
            <b>Celular: </b> {{ $cliente->celular }}<br>
        </div>
        <br><br>

        <!-- Datos de la Cuota -->
            <strong>DATOS DE LA CUOTA</strong><br><br>
            <b>Numero de Cuota: </b> {{ $pago->referencia_pago}}<br>
            <b>Fecha Programada: </b> {{ $fecha_pago_programado }}<br>
            <b>Monto de la Cuota: </b> {{ $ajuste->divisa . ' '. number_format($pago->monto_cuota,2, '.', '.') }}<br>
            <div style="height: 10px"></div>

        <!-- Datos del pago Parcial -->

        @if($pago->metodo_pago === 'Pago parcial')
        <br><br>
            <strong>PAGOS PARCIALES REALIZADOS</strong><br><br>
            @foreach($pago->pagosParciales as $pago_parcial)
                <b>Fecha Pago Parcial: </b> {{ \Carbon\Carbon::parse($pago_parcial->fecha_pago)->format('d/m/Y') }}<br>
                <b>Monto Pagado: </b> {{ $ajuste->divisa . ' '. number_format($pago_parcial->monto_pagado,2, '.', '.') }}<br>
                <div style="height: 5px"></div>
                <br>
            @endforeach
            <div style="height: 10px"></div>
        @endif
        <br><br>

        <!-- Datos del pago -->
        <strong>DATOS DEL PAGO</strong><br><br>
        <b>Fecha Cancelado: </b> {{ $fecha_cancelado }}<br>
        <b>Metodo de Pago: </b> {{ $pago->metodo_pago }}<br>


        @php
            $montoCuota = $pago->monto_cuota;
            $monto_total_pagado = $pago->monto_total_pagado;
            $tieneMora = false;
            $diasDevengado = false;
            if($monto_total_pagado !== $montoCuota) {
                if($pago->monto_interes <= 0) {
                    $diasDevengado = true;
                }else{
                    $tieneMora = true;
                }
            }else {
                $tieneMora = false;
            }
        @endphp

        @if($tieneMora)
          <b>MORA: </b> {{ $ajuste->divisa . ' ' . number_format($monto_total_pagado - $montoCuota, 2, '.', '.') }}<br>
        @endif

        @if($diasDevengado)
          <b>DÍAS DEVENGADOS: </b> {{ $ajuste->divisa . ' ' . number_format($monto_total_pagado - $montoCuota, 2, '.', '.') }}<br>
        @endif

        <div style="height: 5px"></div>
        <b>MONTO TOTAL PAGADO: </b>
        {{ $ajuste->divisa . ' ' . number_format($monto_total_pagado, 2, '.', '.') }}
        <br>

        <br><br>

        <!-- Pie de Pagina -->
        <div class="footer">
            <b>GRACIAS POR SU PREFERENCIA.</b><br>
            <small>
                Atendido por el Usuario: {{ Auth::user()->name }}<br>
                Impreso en Fecha y Hora: {{ \Carbon\Carbon::now()->timezone('-04:00')->format('d/m/Y H:i') }}<br><br>
            </small>
        </div>
    </body>
</html>
