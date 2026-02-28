<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\SessaoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware(['auth', 'nocache'])->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pacientes
    Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
    Route::get('/cadastrar', [PacienteController::class, 'create'])->name('pacientes.create');
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    Route::get('/pacientes/{id}', [PacienteController::class, 'show'])->name('pacientes.show');
    Route::get('/api/pacientes', [PacienteController::class, 'apiIndex'])->name('api.pacientes');
    Route::patch('/pacientes/{id}/status', [PacienteController::class, 'updateStatus'])->name('pacientes.updateStatus');



    // Sessões 
    Route::prefix('pacientes/{paciente}')->group(function () {
    Route::get('/sessoes', [SessaoController::class, 'index'])->name('sessoes.index');
    Route::post('/sessoes', [SessaoController::class, 'store'])->name('sessoes.store');
    Route::put('/sessoes/{sessao}', [SessaoController::class, 'update'])->name('sessoes.update');
});


});





