<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoParcial extends Model
{
    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }
}
