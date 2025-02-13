<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
  

    public function index()
    {
        return view('forms.form-registro');
    }

    public function showLogin (){
        return view('Auth.login');
    }
    
    public function criarUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',            
            'email' => 'required|email|unique:usuarios,email', 
            'telefone' => 'required|string|max:15',       
            'senha' => 'required|string|min:6',            
            'foto' => 'nullable|string',                    
            'cpf' => 'required|string|unique:usuarios,cpf|size:11', 
        ]);
        
        $validatedData['role'] = 'usuario';
        $validatedData['ativo']=true;

        $usuario = Usuario::create($validatedData);
        
        return redirect()->route('form.login');
        
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
