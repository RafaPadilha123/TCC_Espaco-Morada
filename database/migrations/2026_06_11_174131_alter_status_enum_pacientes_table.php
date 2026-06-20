<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    DB::statement("ALTER TABLE pacientes MODIFY status ENUM('ativo','inativo','em pausa') DEFAULT 'ativo'");
}

public function down(): void
{
    DB::statement("ALTER TABLE pacientes MODIFY status ENUM('ativo','inativo') DEFAULT 'ativo'");
}
};
