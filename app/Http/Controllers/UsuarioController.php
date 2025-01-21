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
        return view('forms.form-registro');
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
       
        $validatedData['senha'] = bcrypt($validatedData['senha']);
        $validatedData['role'] = 'usuario';
        $usuario = Usuario::create($validatedData);

    return view('forms.form-registroItem');
        
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
