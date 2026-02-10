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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestamo_id')->constrained('prestamos')->onDelete('cascade');
            $table->date('fecha_vencimiento');
            $table->decimal('saldo_capital', 15, 2);
            $table->decimal('monto_capital', 15, 2);
            $table->decimal('monto_interes', 15, 2);
            $table->decimal('monto_cuota', 15, 2);
            $table->string('metodo_pago', 150);
            $table->string('referencia_pago', 200);
            $table->date('fecha_cancelado')->nullable();
            $table->decimal('monto_total_pagado', 15, 2)->default(0);
            $table->string('estado', 50)->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
