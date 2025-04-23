<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\UserRole;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string|min:6',
        ]);

        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['senha']])) {
            $user = Auth::user();
            if ($user->role === UserRole::ADMIN) {
                return redirect()->route('admin.principal')->with('success', 'Bem-vindo, ' . $user->name . '!');
            } elseif ($user->role === UserRole::USER) {
                return redirect()->route('usuario.home')->with('success', 'Bem-vindo, ' . $user->name . '!');
            } elseif ($user->role === UserRole::PARCEIRO) {
                return redirect()->route('parceiro.home')->with('success', 'Bem-vindo, ' . $user->name . '!');
            } else {
                return redirect()->route('form.login')->with('error', 'Função de usuário não reconhecida.');
            }
        }

        return back()->with('error', 'Credenciais inválidas. Por favor, tente novamente.');
    }

    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    }

    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function showForgotPasswordForm()
    {
        return view('Auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('Auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'senha' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('form.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
