<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => trim((string) $request->input('email')),
        ]);

        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required'     => 'Token de redefinição inválido ou ausente.',
            'email.required'     => 'Informe o seu endereço de e-mail.',
            'email.email'        => 'Informe um endereço de e-mail válido.',
            'password.required'  => 'Informe a nova senha.',
            'password.confirmed' => 'A confirmação da nova senha não confere.',
            'password.min'       => 'A nova senha deve ter pelo menos :min caracteres.',
        ]);

        $emailNormalizado = mb_strtolower($request->input('email'));
        $usuario = User::whereRaw('LOWER(TRIM(email)) = ?', [$emailNormalizado])->first();
        $emailParaBroker = $usuario ? $usuario->email : $request->input('email');

        $status = Password::reset(
            [
                'email'                 => $emailParaBroker,
                'password'              => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'token'                 => $request->input('token'),
            ],
            function (User $user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', 'Sua senha foi redefinida com sucesso! Agora você já pode entrar com a nova senha.');
        }

        $mensagemErro = match ($status) {
            Password::INVALID_TOKEN => 'Este link de redefinição de senha é inválido ou já expirou. Solicite um novo link.',
            Password::INVALID_USER  => 'Não encontramos nenhum usuário cadastrado com este endereço de e-mail.',
            default                 => __($status),
        };

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $mensagemErro]);
    }
}
