<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $exact = $request->input('exact');

        $pacientes = Paciente::orderByRaw("
            CASE 
                WHEN status = 'ativo' THEN 1
                WHEN status = 'pausa' THEN 2
                WHEN status = 'inativo' THEN 3
                ELSE 4
            END
        ")->orderBy('nome');

        if ($query) {
    if ($exact && $exact == 'true') {
        $pacientes->whereRaw('LOWER(nome) = ?', [strtolower($query)]);
    } else {
        $pacientes->where('nome', 'like', "$query%");
      }
    }

        return response()->json($pacientes->paginate(6));
    }

    public function apiIndex(Request $request)
    {
        return $this->index($request);
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);

        $sessoes = \App\Models\Sessao::where('paciente_id', $paciente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('prontuario', compact('paciente', 'sessoes'));
    }

    public function create()
    {
        return view('cadastrar');
    }


    public function destroy($id)
    {
    $paciente = Paciente::findOrFail($id);

    $temSessoes = \App\Models\Sessao::where('paciente_id', $paciente->id)->exists();

    if ($temSessoes) {
        return response()->json([
            'success' => false,
            'message' => 'Não é possível excluir um paciente com sessões cadastradas.'
        ], 400);
    }

    $paciente->delete();

    return response()->json([
        'success' => true,
        'message' => 'Paciente excluído com sucesso!'
    ]);
    }
    
    
    public function updateStatus(Request $request, $id)
{
    $paciente = \App\Models\Paciente::findOrFail($id);

    $request->validate([
        'status' => 'required|in:ativo,pausa,inativo'
    ]);

    $paciente->update(['status' => $request->status]);

    return response()->json([
        'success' => true,
        'novo_status' => $paciente->status
    ]);
}
     
    public function store(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf),
            'telefone' => preg_replace('/[^0-9]/', '', $request->telefone)
        ]);

        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:pacientes,cpf', 
            'email' => 'required|email|max:255|unique:pacientes,email',
            'telefone' => 'required|string|max:15',
            'status' => 'required|in:ativo,pausa,inativo',
        ]);

        $paciente = Paciente::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ], 201);
        }

        return redirect()->back()->with('success', 'Paciente cadastrado com sucesso!');
    }
}

