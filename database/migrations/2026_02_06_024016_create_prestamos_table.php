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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');

            $table->decimal('monto_prestado', 10, 2);
            $table->decimal('tasa_interes', 5, 2);
            $table->string('modalidad_pago', 50);
            $table->string('modalidad_amortizacion', 50);
            $table->integer('nro_cuotas');
            $table->decimal('monto_interes_total', 10, 2);
            $table->decimal('monto_total_a_pagar',10, 2);
            $table->date('fecha_inicio');
            $table->enum('estado',['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
