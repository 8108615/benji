<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);


        User::create([
            'name' => 'Erick Fernando Morales Gil',
            'email' => 'erick@gmail.com',
            /*'nombres' => 'Erick Fernando',
            'apellidos' => 'Morales Gil',
            'tipo_documento' => 'DNI',
            'numero_documento' => '12345678',
            'celular' => '76658531',
            'direccion' => 'Av Cumavi',
            'fecha_nacimiento' => '1990-12-20',
            'genero' => 'Masculino',
            'foto_perfil' => null,
            'contacto_nombre' => 'Anahi Morales',
            'contacto_telefono' => '987654321',
            'contacto_relacion' => 'Friend',
            'estado' => 'Activo',
            */
            'password' => bcrypt('12345678'),
        ])->assignRole('SUPER ADMINISTRADOR');

        Ajuste::create([
            'nombre' => 'Erick',
            'descripcion' => 'Sistema de prestamos',
            'direccion' => 'Av cumavi',
            'telefono' => '76658531',
            'email' => 'erickfer@gmail.com',
            'divisa' => 'Bs',
            'logo' => null,
            'web' => 'https://www.erick.com',
            'interes' => 10.00,
            'mora' => 2.00,
            'dias_gracia' => 5,
            'dias_notificacion' => 5,

        ]);

        Cliente::factory(30)->create();

        Categoria::create(['nombre' => 'Prestamo Educativo', 'porcentaje' => 5.00]);
        Categoria::create(['nombre' => 'Prestamo para Viaje', 'porcentaje' => 10.00]);
        Categoria::create(['nombre' => 'Prestamo para Salud', 'porcentaje' => 5.00]);
        Categoria::create(['nombre' => 'Prestamo Personal', 'porcentaje' => 15.00]);
        Categoria::create(['nombre' => 'Prestamo Comercial', 'porcentaje' => 25.00]);
        Categoria::create(['nombre' => 'Prestamo Hipotecario', 'porcentaje' => 25.00]);
        Categoria::create(['nombre' => 'Prestamo Automotriz', 'porcentaje' => 10.00]);
        Categoria::create(['nombre' => 'Prestamo Microcrédito', 'porcentaje' => 8.00]);

        $prestamo = Prestamo::create([
            'cliente_id' => 1,
            'categoria_id' => 1,
            'monto_prestado' => 1000.00,
            'tasa_interes' => 10.00,
            'modalidad_pago'=> 'Mensual',
            'modalidad_amortizacion'=> 'Francés',
            'nro_cuotas' => 12,
            'monto_interes_total'=> '54.99',
            'monto_total_a_pagar'=> '1054.99',
            'fecha_inicio' => '2026-02-25',
            'estado' => 'pendiente',
        ]);

        Pago::insert([
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-03-25', 'saldo_capital' => 1000.00, 'monto_capital' => 79.58, 'monto_interes' => 8.33, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 1', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-04-25', 'saldo_capital' => 920.42, 'monto_capital' => 80.25, 'monto_interes' => 7.67, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 2', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-05-25', 'saldo_capital' => 840.17, 'monto_capital' => 80.91, 'monto_interes' => 7.00, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 3', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-06-25', 'saldo_capital' => 759.26, 'monto_capital' => 81.59, 'monto_interes' => 6.33, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 4', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-07-25', 'saldo_capital' => 677.67, 'monto_capital' => 82.27, 'monto_interes' => 5.65, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 5', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-08-25', 'saldo_capital' => 595.40, 'monto_capital' => 82.95, 'monto_interes' => 4.96, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 6', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-09-25', 'saldo_capital' => 512.45, 'monto_capital' => 83.65, 'monto_interes' => 4.27, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 7', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-10-25', 'saldo_capital' => 428.80, 'monto_capital' => 84.34, 'monto_interes' => 3.57, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 8', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-11-25', 'saldo_capital' => 344.46, 'monto_capital' => 85.05, 'monto_interes' => 2.87, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 9', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2026-12-25', 'saldo_capital' => 259.41, 'monto_capital' => 85.75, 'monto_interes' => 2.16, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 10', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2027-01-25', 'saldo_capital' => 173.66, 'monto_capital' => 86.47, 'monto_interes' => 1.45, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 11', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
            ['prestamo_id' => $prestamo->id, 'fecha_vencimiento' => '2027-02-25', 'saldo_capital' => 87.19, 'monto_capital' => 87.19, 'monto_interes' => 0.73, 'monto_cuota' => 87.92, 'metodo_pago' => '-', 'referencia_pago' => 'Cuota: 12', 'fecha_cancelado' => null, 'monto_total_pagado' => 0.00, 'estado' => 'pendiente', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
