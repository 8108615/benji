<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }

    public function pagosParciales()
    {
        return $this->hasMany(PagoParcial::class);
    }
}
