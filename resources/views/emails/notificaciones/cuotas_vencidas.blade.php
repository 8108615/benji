<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Cuotas Vencidas</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, sans-serif; color:1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellspacing="0" cellpadding="0"
                    style="max-width: 620px;width: 100%; background: #ffffff; border-radius: 12px;overflow: hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background: #1d4ed8;padding:20px 24px; color: #ffffff;">
                            <h1 style="margin: 0;font-size:20px;line-height:1.2;">Recordatorio de Pago</h1>
                            <p style="margin: 6px 0 0 0;font-size:14px;opacity:0.95;">Cuotas vencidas de su Prestamo</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <p style="margin:0 0 14px 0; font-size: 15px;">Estimado/a
                                <strong>{{ $resumen['cliente']->nombres }}
                                    {{ $resumen['cliente']->apellidos }} </strong>,
                            </p>

                            <p style="margin:0 0 16px 0; font-size: 14px; line-height:1.6;">
                                Le Informamos que, a la fecha {{ $resumen['fecha_actual'] }}, su cuenta registrta cuotas vencidas.
                                Le Solicitamos regularizar el pago para evitar recargos adicionales.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="border-collapse: collapse; margin-top: 6px 0 18px 0;">
                                <tr>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;background:#f9fafb;font-size:13px;">
                                        Cuotas Vencidas</td>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;text-align:right;font-size:13px;font-weight:700;">
                                        {{ $resumen['cuotas_vencidas_total'] }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;background:#f9fafb;font-size:13px;">
                                        Monto  Vencido total</td>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;text-align:right;font-size:13px;font-weight:700;color:#b91c1c;">
                                        {{ $resumen['divisa'] }} {{ number_format($resumen['monto_vencido_total'], 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;background:#f9fafb;font-size:13px;">
                                        Primer Vencimiento </td>
                                    <td style="padding: 10px;border:1px solid #e5e7eb;text-align:right;font-size:13px;font-weight:700;">
                                        {{ $resumen['primer_vencimiento_formateado'] }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 18px 0 0 0;font-size:14px;line-height:1.6;">
                                Si ya Realizo el pago, Por favor ignore este mensaje. En caso contrario, comuniquese con
                                nuesta oficina para brindarle asistencia inmediata.
                            </p>

                            <p style="margin: 18px 0 0 0;font-size:14px;">Atentamente,<br><strong>Area de
                                Cobranza</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background: #f9fafb;padding:14px 24px; font-size: 12px;color:#6b7280;border-top:1px solid #e5e7eb;">
                            Este es un mensaje automatico de notificaciones de cuotas vencidas.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
