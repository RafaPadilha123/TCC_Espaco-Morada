<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardButtonsTest extends DuskTestCase
{
    public function test_botao_novo_paciente_funciona()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {

            $browser->loginAs($user)

                    ->visit('/dashboard')

                    ->press('+ Novo Paciente')

                    ->assertPathIs('/pacientes/create');
        });
    }
    
    public function test_logout_funciona()
{
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {

        $browser->loginAs($user)

                ->visit('/dashboard')

                ->press('Sair')

                ->assertPathIs('/login');
    });
}

public function test_botao_grafico_abre_modal()
{
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {

        $browser->loginAs($user)

                ->visit('/dashboard')

                ->click('#btnGraficoKeywords')

                ->assertVisible('#graficoModal');
    });
}

public function test_paginacao_funciona()
{
    $user = User::factory()->create();

    $this->browse(function (Browser $browser) use ($user) {

        $browser->loginAs($user)

                ->visit('/dashboard')

                ->click('#nextPageBtn')

                ->pause(500)

                ->assertPresent('#patientsContainer');
    });
}

}
