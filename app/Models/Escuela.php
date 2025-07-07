<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_escuela'];

    public function notas()
    {
        return $this->hasMany(Nota::class, 'escuela_id');
    }
} 