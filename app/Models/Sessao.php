<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sessao extends Model
{
    protected $table = 'sessoes'; 

    protected $fillable = [
        'paciente_id',
        'numero_sessao',
        'data_sessao',
        'registro',
        'palavras_chave',
    ];

    protected $casts = [
        'palavras_chave' => 'array',
        'data_sessao' => 'date',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}


