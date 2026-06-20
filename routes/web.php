<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\SessaoController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| ROTA INICIAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return redirect()->route('login');

});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
)->name('login.post');

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'nocache'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'dashboard']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');

    /*
    |--------------------------------------------------------------------------
    | PACIENTES
    |--------------------------------------------------------------------------
    */

    // LISTAR PACIENTES
    Route::get(
        '/pacientes',
        [PacienteController::class, 'index']
    )->name('pacientes.index');

    // API PACIENTES
    Route::get(
        '/api/pacientes',
        [PacienteController::class, 'apiIndex']
    )->name('api.pacientes');

    // FORMULÁRIO CADASTRO
    Route::get(
        '/cadastrar',
        [PacienteController::class, 'create']
    )->name('pacientes.create');

    // SALVAR PACIENTE
    Route::post(
        '/pacientes',
        [PacienteController::class, 'store']
    )->name('pacientes.store');

    // MOSTRAR PRONTUÁRIO
    Route::get(
        '/pacientes/{id}',
        [PacienteController::class, 'show']
    )->name('pacientes.show');

    // EDITAR PACIENTE
    Route::get(
        '/pacientes/{id}/editar',
        [PacienteController::class, 'edit']
    )->name('pacientes.edit');

    // ATUALIZAR PACIENTE
    Route::put(
        '/pacientes/{id}',
        [PacienteController::class, 'update']
    )->name('pacientes.update');

    // EXCLUIR PACIENTE
    Route::delete(
        '/pacientes/{id}',
        [PacienteController::class, 'destroy']
    )->name('pacientes.destroy');

    // ALTERAR STATUS
    Route::patch(
        '/pacientes/{id}/status',
        [PacienteController::class, 'updateStatus']
    )->name('pacientes.updateStatus');

    /*
    |--------------------------------------------------------------------------
    | SESSÕES
    |--------------------------------------------------------------------------
    */

    Route::prefix('pacientes/{paciente}')->group(function () {

        // LISTAR SESSÕES
        Route::get(
            '/sessoes',
            [SessaoController::class, 'index']
        )->name('sessoes.index');

        // NOVA SESSÃO
        Route::post(
            '/sessoes',
            [SessaoController::class, 'store']
        )->name('sessoes.store');

        // EDITAR SESSÃO
        Route::put(
            '/sessoes/{sessao}',
            [SessaoController::class, 'update']
        )->name('sessoes.update');

    });

});
