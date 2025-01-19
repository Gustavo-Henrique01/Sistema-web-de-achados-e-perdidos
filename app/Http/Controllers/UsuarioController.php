<?php

namespace App\Http\Controllers;
use App\Models\Usuario;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function criarUsuario(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',             // Nome do usuário
            'email' => 'required|email|unique:usuarios,email', // Validação de e-mail único
            'telefone' => 'required|string|max:15',          // Telefone do usuário
            'senha' => 'required|string|min:8',              // Senha com no mínimo 8 caracteres
            'foto' => 'nullable|string',                     // Foto é opcional
            'cpf' => 'required|string|unique:usuarios,cpf|size:11', // CPF único e com 11 caracteres
        ]);
    
        // Criptografa a senha antes de salvar no banco de dados
        $validatedData['senha'] = bcrypt($validatedData['senha']);
    
        // Define a role como 'usuario'
        $validatedData['role'] = 'usuario';
    
        try {
            // Criação do usuário com os dados validados
            $usuario = Usuario::create($validatedData);
    
            // Retorna resposta de sucesso com os dados do usuário
            return response()->json([
                'mensagem' => 'Usuário criado com sucesso!',
                'usuario' => $usuario
            ], 201);
        } catch (\Exception $e) {
            // Caso ocorra algum erro durante a criação, retorna uma resposta de erro
            return response()->json([
                'mensagem' => 'Erro ao criar usuário.',
                'erro' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
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
    public function edit(string $id)
    {
        //
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
