<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use Carbon\Carbon;

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

            $pacientes->where(function($q) use ($query, $exact) {

                if ($exact && $exact == 'true') {

                    $q->whereRaw(
                        'LOWER(nome) = ?',
                        [strtolower($query)]
                    );

                } else {

                    $q->where(
                        'nome',
                        'like',
                        "%$query%"
                    );

                }

                $q->orWhereHas('sessoes', function($sessao) use ($query) {

                    $sessao->where(
                        'palavras_chave',
                        'like',
                        "%$query%"
                    );

                });

            });

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

    public function edit($id)
    {
        $paciente = Paciente::findOrFail($id);

        return view('cadastrar', compact('paciente'));
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
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:15',
            'status' => 'required|in:ativo,pausa,inativo',
        ], [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter 11 números.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $dataInativacao = $request->status === 'inativo'
            ? now()
            : null;

        $paciente = Paciente::create([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'status' => $request->status,
            'data_inativacao' => $dataInativacao,
        ]);

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ], 201);

        }

        return redirect()->back()
            ->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $request->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf),
            'telefone' => preg_replace('/[^0-9]/', '', $request->telefone)
        ]);

        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:pacientes,cpf,' . $paciente->id,
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:15',
            'status' => 'required|in:ativo,pausa,inativo',
        ], [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter 11 números.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $paciente->update([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'status' => $request->status,
            'data_inativacao' => $request->status === 'inativo'
                ? now()
                : null,
        ]);

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'redirect' => '/pacientes/' . $paciente->id
            ]);

        }

        return redirect('/pacientes/' . $paciente->id)
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);

        $ultimaSessao = \App\Models\Sessao::where(
            'paciente_id',
            $paciente->id
        )
        ->orderBy('data_sessao', 'desc')
        ->first();

        if ($ultimaSessao) {

            if ($paciente->status !== 'inativo') {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Paciente precisa estar inativo para ser excluído.'
                ], 403);

            }

            $dataUltimaSessao =
                Carbon::parse($ultimaSessao->data_sessao);

            $cincoAnosAtras =
                now()->subYears(5);

            if ($dataUltimaSessao > $cincoAnosAtras) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Paciente só pode ser excluído se a última sessão tiver mais de 5 anos.'
                ], 403);

            }

            \App\Models\Sessao::where(
                'paciente_id',
                $paciente->id
            )->delete();

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

        $paciente->status = $request->status;

        if ($request->status === 'inativo') {

            $paciente->data_inativacao =
                $request->data_inativacao
                    ? Carbon::parse($request->data_inativacao)
                    : Carbon::now();

        } else {

            $paciente->data_inativacao = null;

        }

        $paciente->save();

        return response()->json([
            'success' => true,
            'novo_status' => $paciente->status
        ]);
    }
}
