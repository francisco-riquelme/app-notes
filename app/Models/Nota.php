<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nota_1',
        'nota_2', 
        'nota_3',
        'nota_final',
        'observaciones',
        'escuela'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
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
