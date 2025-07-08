<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'codigo_funcionario',
        'nombre_estudiante',
        'grado',
        'unidad',
        'situacion',
        'id_posicion',
        'grupo',
        'sede',
        'sexo',
        'nota_1',
        'nota_2', 
        'nota_3',
        'nota_final',
        'observaciones',
        'escuela',
        'escalafon_id',
        'fecha_carga',
        'admin_id'
    ];

    protected $casts = [
        'fecha_carga' => 'date',
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el admin que cargó los datos
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function escuela()
    {
        return $this->belongsTo(Escuela::class, 'escuela_id');
    }

    public function escalafon()
    {
        return $this->belongsTo(Escalafon::class, 'escalafon_id');
    }
}
