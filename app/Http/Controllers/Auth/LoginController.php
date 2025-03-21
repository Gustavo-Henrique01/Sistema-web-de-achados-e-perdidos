<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\UserRole;


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

                return redirect()->route('admin.principal');
            }elseif ($user->role === UserRole::USER) {
                return redirect()->route('usuario.home');
            } elseif ($user->role === UserRole::PARCEIRO) {
                return redirect()->route('');

            }
            else{
                return redirect()->route('form.login');
            }

           return redirect()->route('usuario.home'); 
        }

    }

    

    public function sair (Request $request)
    {
        Auth::logout(); // Faz logout do usuário autenticado

        $request->session()->invalidate(); // Invalida a sessão
        $request->session()->regenerateToken(); // Regenera o token CSRF

       // return redirect('/login')->with('success', 'Logout realizado com sucesso!');
    }

    public function showLoginForm() {
        return view('Auth.login');
    }

    
    
}
