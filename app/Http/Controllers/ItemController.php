<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Localizacao;
use App\Models\Categoria;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
   

    public function index()
{
    $categorias = Categoria::all();
    return view('forms.form-registroItem',compact('categorias'));
    }
 
    
    public function registroItem(Request $request)
    {
        // Validação da localização
        $validatedLocalizacao = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255', // Endereço formatado pela API
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
    
        // Cria a localização
        $localizacao = Localizacao::create($validatedLocalizacao);
    
        // Validação do item
        $validatedItem = $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'data_perdido' => $request->tipo === 'perdido' ? 'required|date' : 'nullable|date',
            'data_encontrado' => $request->tipo === 'achado' ? 'required|date' : 'nullable|date',
            'referencial' => 'required|string|max:1000',
        ]);
    
        // Salva a foto no storage
        if ($request->hasFile('foto')) {
            $validatedItem['foto'] = $request->file('foto')->store('imagens', 'public');
        }
    
        // Define a data de perdido ou encontrado com base no tipo
        if ($validatedItem['tipo'] === 'perdido') {
            $validatedItem['data_perdido'] = $request->data_perdido;
            $validatedItem['data_encontrado'] = null;
        } else {
            $validatedItem['data_encontrado'] = $request->data_encontrado;
            $validatedItem['data_perdido'] = null;
        }
    
        // Adiciona o ID da localização e do usuário autenticado
        $validatedItem['id_localizacao'] = $localizacao->id;
        $validatedItem['id_usuario'] = auth()->id();
    
        // Define o status como "pendente" e aprovado como "false"
        $validatedItem['status'] = 'pendente';
        $validatedItem['aprovado'] = false;
        $validatedItem['aprovado_em'] = null;
    
        // Cria o item
        $item = Item::create($validatedItem);
    
        return redirect()->route('usuario.home')->with('success', 'Item cadastrado com sucesso!');
    }
    public function listarItens() {
        $itens = Item::where('status', 'aprovado')->get();
        return view('listagens.listar-itens',compact('itens') );
    }

    public function listarItensUsuario()
    {
  
        $usuario = Auth::user();
        $itens = $usuario->itens;

        return view('usuario.perfil-usuario', compact('usuario','itens'));
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
