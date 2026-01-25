<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Cliente;
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

        Role::create(['name' => 'SUPER ADMINISTRADOR']);
        Role::create(['name' => 'ADMINISTRADOR']);
        Role::create(['name' => 'PRESTAMISTA']);
        Role::create(['name' => 'FACTURADOR']);
        Role::create(['name' => 'COBRADOR']);
        Role::create(['name' => 'CLIENTE']);


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

        ]);

        Cliente::factory(100)->create();
    }
}
