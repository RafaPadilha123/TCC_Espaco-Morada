<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'status',
        'data_inativacao',
    ];
    
    protected $casts = [
    'data_inativacao' => 'date',
    ];

    public function sessoes()
    {
         return $this->hasMany(Sessao::class);
    }
}

