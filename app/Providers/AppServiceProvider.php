<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expiracao = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);
            $nome = $notifiable->name ?? 'usuário';

            return (new MailMessage)
                ->subject('Redefinição de senha — Car Space')
                ->greeting("Olá, {$nome}!")
                ->line('Recebemos uma solicitação para redefinir a senha da sua conta no Car Space.')
                ->action('Redefinir Senha', $url)
                ->line("Este link de redefinição de senha expira em {$expiracao} minutos.")
                ->line('Se você não solicitou a redefinição de senha, nenhuma ação adicional é necessária.')
                ->salutation('Atenciosamente, Equipe Car Space');
        });
    }
}
