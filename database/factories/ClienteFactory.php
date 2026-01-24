<?php

namespace Database\Factories;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $nombres = $this->faker->firstName();
        $apellidos = $this->faker->lastName();
        $numero_documento = $this->faker->unique()->numerify('########');

        $usuario = User::create([
            'name' => $nombres . ' ' . $apellidos,
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt($numero_documento), //contraseña por defecto
            'estado' => 'Activo',
        ]);

        $usuario->assignRole('CLIENTE');

        return [
            'user_id' => $usuario->id,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'tipo_documento' => $this->faker->randomElement(['DNI', 'Pasaporte', 'Carnet de Extranjeria', 'RUC', 'Carnet de Identidad']),
            'numero_documento' => $numero_documento,
            'celular' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'fecha_nacimiento' => $this->faker->date(),
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'foto_perfil' => null,
            'contacto_nombre' => $this->faker->name(),
            'contacto_telefono' => $this->faker->phoneNumber(),
            'contacto_relacion' => $this->faker->randomElement(['Familia', 'Amigo', 'Compañero de trabajo']),
        ];
    }
}
