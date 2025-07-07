<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escalafon extends Model
{
    use HasFactory;

    protected $table = 'escalafon';
    protected $fillable = ['nombre'];

    public function notas()
    {
        return $this->hasMany(Nota::class, 'escalafon_id');
    }
} 