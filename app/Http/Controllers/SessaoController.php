<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Sessao;
use Illuminate\Http\Request;

class SessaoController extends Controller
{
    public function store(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'numero_sessao'   => 'required|integer',
            'data_sessao'     => 'required|date',
            'registro'        => 'required|string',
            'palavras_chave'  => 'nullable|string', 
        ]);

        $sessao = $paciente->sessoes()->create($data);

        return response()->json($sessao, 201);
    }

    public function update(Request $request, Paciente $paciente, Sessao $sessao)
    {
        $data = $request->validate([
            'numero_sessao'   => 'required|integer',
            'data_sessao'     => 'required|date',
            'registro'        => 'required|string',
            'palavras_chave' => 'nullable|string',
        ]);

        $sessao->update($data);

        return response()->json($sessao);
    }
}

