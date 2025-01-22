<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function login(Request $request)
{
    $validatedData = $request->validate([
        'email' => 'required|email',
        'senha' => 'required|string|min:8',
    ]);

    // Autenticação usando 'email' e 'senha'
    if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['senha']])) {
        return redirect()->route('cadastro-item');
    }

    return redirect()->route('login')->withErrors(['email' => 'Credenciais inválidas']);
}


    /**
     * Realiza o logout do usuário.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // Redireciona para a página de login após o logout
    }



    public function index()
    {
        return view('forms.form-registro');
    }

    public function showLogin (){
        return view('Auth.login');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function criarUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',            
            'email' => 'required|email|unique:usuarios,email', 
            'telefone' => 'required|string|max:15',       
            'senha' => 'required|string|min:8',            
            'foto' => 'nullable|string',                    
            'cpf' => 'required|string|unique:usuarios,cpf|size:11', 
        ]);
        
        // Criptografa a senha
       
        // Define o papel como usuário
        $validatedData['role'] = 'usuario';
        
        // Cria o usuário
        $usuario = Usuario::create($validatedData);

       
        
        return redirect()->route('login');
        
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function listarItens()
    {
  
        $user = Auth::user();

        // Obtém todos os itens do usuário logado
        $itens = $user->itens; // Acessa os itens do usuário logado

        // Retorna a view com os itens
        return view('listagens.itens-cadastrados-usuario', compact('itens'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
