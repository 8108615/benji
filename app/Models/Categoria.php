<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
