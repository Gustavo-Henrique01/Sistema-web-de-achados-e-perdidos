<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
   



    public function index()
    {
        //
    }

    
   
    public function registroItem(Request $request)
    {
        // Validação do endereço
        $validatedEndereco = $request->validate([
            'rua' => 'required|string|max:100',
            'numero' => 'nullable|string|max:10',
            'bairro' => 'required|string|max:255',
            'referencia' => 'nullable|string|max:1000',
        ]);

        $validatedEndereco['cidade'] = 'Campo Grande';
        $validatedEndereco['estado'] = 'Mato Grosso do sul';

        
            
        // Criação do endereço
        $endereco = Endereco::create($validatedEndereco);
    
        // Validação do item
        $validatedItem = $request->validate([
            'categoria' => 'required|string|max:255',
            'foto' => 'nullable|file',
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'status' => 'required|in:pendente,resolvido',
        ]);
    
        // Adiciona o ID do endereço e do usuário autenticado
        $validatedItem['id_endereco'] = $endereco->id;
        $validatedItem['id_usuario'] = auth()->id();
    
        // Criação do item
        $item = Item::create($validatedItem);
    
    }
    



    public function create()
    {
        //
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
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
