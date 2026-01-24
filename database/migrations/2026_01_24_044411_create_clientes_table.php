<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('nombres');
            $table->string('apellidos');
            $table->enum('tipo_documento', ['DNI', 'Pasaporte', 'Carnet de Extranjeria', 'RUC', 'Carnet de Identidad']);
            $table->string('numero_documento')->unique();
            $table->string('celular');
            $table->string('direccion');
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['Masculino', 'Femenino']);
            $table->string('foto_perfil')->nullable();
            $table->string('contacto_nombre');
            $table->string('contacto_telefono');
            $table->string('contacto_relacion');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
