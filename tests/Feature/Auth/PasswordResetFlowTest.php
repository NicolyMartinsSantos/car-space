<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_solicitacao_com_email_cadastrado_inclusive_com_maiusculas_e_espacos_funciona(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'name' => 'Nicoly Martins',
            'email' => 'nicoly.teste@example.com',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => '  Nicoly.Teste@EXAMPLE.com  ',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', 'Enviamos um link de redefinição de senha para o seu e-mail.');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'nicoly.teste@example.com',
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $mail = $notification->toMail($user);

            $this->assertSame('Redefinição de senha — Car Space', $mail->subject);
            $this->assertStringContainsString('/reset-password/' . $notification->token, $mail->actionUrl);
            $this->assertStringContainsString(urlencode($user->email), $mail->actionUrl);

            return true;
        });
    }

    public function test_solicitacao_com_email_nao_cadastrado_retorna_mensagem_clara(): void
    {
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'inexistente@example.com',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Não encontramos nenhum usuário cadastrado com este endereço de e-mail.',
        ]);

        $this->assertDatabaseCount('password_reset_tokens', 0);
        Notification::assertNothingSent();
    }

    public function test_fluxo_completo_redefinicao_login_com_nova_senha_e_bloqueio_de_reuso_do_token(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'motorista@example.com',
            'password' => Hash::make('SenhaAntiga@123'),
        ]);

        // 1. Solicitar link
        $this->post('/forgot-password', ['email' => 'Motorista@Example.com'])
            ->assertSessionHasNoErrors();

        $tokenCapturado = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$tokenCapturado) {
            $tokenCapturado = $notification->token;
            return true;
        });
        $this->assertNotNull($tokenCapturado);

        // 2. Abrir tela de nova senha
        $this->get('/reset-password/' . $tokenCapturado . '?email=' . urlencode($user->email))
            ->assertOk()
            ->assertSee('Definir nova senha')
            ->assertSee($user->email);

        // 3. Cadastrar nova senha (inclusive testando e-mail com maiúsculas)
        $resetResponse = $this->post('/reset-password', [
            'token' => $tokenCapturado,
            'email' => 'MOTORISTA@EXAMPLE.COM',
            'password' => 'NovaSenhaSegura@456',
            'password_confirmation' => 'NovaSenhaSegura@456',
        ]);

        $resetResponse
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Sua senha foi redefinida com sucesso! Agora você já pode entrar com a nova senha.');

        // 4. Senha atualizada no banco e token removido para impedir reutilização
        $user->refresh();
        $this->assertTrue(Hash::check('NovaSenhaSegura@456', $user->password));
        $this->assertFalse(Hash::check('SenhaAntiga@123', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        // 5. Reutilização do mesmo token deve falhar
        $reusoResponse = $this->post('/reset-password', [
            'token' => $tokenCapturado,
            'email' => $user->email,
            'password' => 'OutraSenha@789',
            'password_confirmation' => 'OutraSenha@789',
        ]);
        $reusoResponse->assertSessionHasErrors([
            'email' => 'Este link de redefinição de senha é inválido ou já expirou. Solicite um novo link.',
        ]);

        // 6. Login com senha antiga falha
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'SenhaAntiga@123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        // 7. Login com a nova senha funciona normalmente
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'NovaSenhaSegura@456',
        ])->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_rejeita_token_invalido_e_token_expirado(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'expirado@example.com',
            'password' => Hash::make('SenhaOriginal@123'),
        ]);

        // Token inválido
        $resInvalido = $this->post('/reset-password', [
            'token' => 'token-totalmente-invalido',
            'email' => $user->email,
            'password' => 'NovaSenha@123',
            'password_confirmation' => 'NovaSenha@123',
        ]);
        $resInvalido->assertSessionHasErrors([
            'email' => 'Este link de redefinição de senha é inválido ou já expirou. Solicite um novo link.',
        ]);

        // Gerar token válido e retroceder created_at em 61 minutos para simular expiração
        $this->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHasNoErrors();

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $n) use (&$token) {
            $token = $n->token;
            return true;
        });

        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->update(['created_at' => Carbon::now()->subMinutes(61)]);

        $resExpirado = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NovaSenha@123',
            'password_confirmation' => 'NovaSenha@123',
        ]);

        $resExpirado->assertSessionHasErrors([
            'email' => 'Este link de redefinição de senha é inválido ou já expirou. Solicite um novo link.',
        ]);

        // Senha original permanece inalterada
        $user->refresh();
        $this->assertTrue(Hash::check('SenhaOriginal@123', $user->password));
    }
}
