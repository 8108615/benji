<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'celular',
        'direccion',
        'fecha_nacimiento',
        'genero',
        'foto_perfil',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_relacion',
        'deleted_at',
    ];

    public function user()
    {
        // Include soft-deleted users when retrieving the related user
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
