<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_consegue_logar(): void
    {
        $user = User::factory()->create([
            'email' => 'teste@email.com',
            'password' => bcrypt('12345678')
        ]);

        $response = $this->post('/login', [
            'email' => 'teste@email.com',
            'password' => '12345678'
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_usuario_nao_consegue_logar_com_senha_errada(): void
    {
        User::factory()->create([
            'email' => 'teste@email.com',
            'password' => bcrypt('12345678')
        ]);

        $response = $this->post('/login', [
            'email' => 'teste@email.com',
            'password' => 'senhaerrada'
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_usuario_deslogado_nao_acessa_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_usuario_logado_acessa_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_usuario_consegue_deslogar(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response->assertRedirect('/login');

        $this->assertGuest();
    }
}
