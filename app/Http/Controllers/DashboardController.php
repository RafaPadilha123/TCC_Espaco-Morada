<?php

namespace App\Http\Controllers;

use App\Models\Sessao;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $sessoes = Sessao::select('id', 'palavras_chave')->get();

        return view('dashboard', compact('sessoes'));
    }
}
