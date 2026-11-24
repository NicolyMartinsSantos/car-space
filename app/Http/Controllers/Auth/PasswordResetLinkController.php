<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => trim((string) $request->input('email')),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Informe o seu endereço de e-mail.',
            'email.email'    => 'Informe um endereço de e-mail válido.',
        ]);

        $emailNormalizado = mb_strtolower($request->input('email'));

        // Busca case-insensitive compatível com SQLite
        $usuario = User::whereRaw('LOWER(TRIM(email)) = ?', [$emailNormalizado])->first();

        if (! $usuario) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Não encontramos nenhum usuário cadastrado com este endereço de e-mail.',
                ]);
        }

        try {
            $status = Password::sendResetLink([
                'email' => $usuario->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar e-mail de redefinição de senha.', [
                'email' => $usuario->email,
                'mailer' => config('mail.default'),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Não foi possível enviar o e-mail de recuperação no momento. Verifique as configurações de e-mail (SMTP) do servidor.',
                ]);
        }

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Enviamos um link de redefinição de senha para o seu e-mail.');
        }

        $mensagemErro = match ($status) {
            Password::RESET_THROTTLED => 'Aguarde alguns instantes antes de solicitar um novo link de redefinição.',
            Password::INVALID_USER    => 'Não encontramos nenhum usuário cadastrado com este endereço de e-mail.',
            default                   => __($status),
        };

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $mensagemErro]);
    }
}
