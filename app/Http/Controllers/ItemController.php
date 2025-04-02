<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Localizacao;
use App\Models\Categoria;
use App\Models\ItemFoto;
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
            'endereco' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'referencia' => 'required|string|max:1000',
        ]);
    
        // Cria a localização
        $localizacao = Localizacao::create($validatedLocalizacao);
    
        // Validação do item
        $validatedItem = $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'data_perdido' => $request->tipo === 'perdido' ? 'required|date' : 'nullable|date',
            'data_encontrado' => $request->tipo === 'achado' ? 'required|date' : 'nullable|date',
        ]);
    
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
        $validatedItem['user_id'] = auth()->id();
        $validatedItem['status'] = 'pendente';
        $validatedItem['aprovado'] = false;
        $validatedItem['aprovado_em'] = null;
        
    
        // Cria o item
        $item = Item::create($validatedItem);
    
        // Salva as fotos
        if ($request->hasFile('fotos')) {
            $fotos = [];
            
            // Processar as fotos individuais
            foreach ($request->file('fotos') as $key => $foto) {
                if ($foto->isValid()) {
                    $fotos[] = $foto;
                }
            }
            
            $totalFotos = count($fotos);
            
            if ($totalFotos > 3) {
                return redirect()->back()->with('error', 'Você pode enviar no máximo 3 fotos.');
            }
            
            foreach ($fotos as $key => $foto) {
                $path = $foto->store('imagens', 'public');
                
                $isPrincipal = false;
                if ($request->has('foto_principal_index') && $request->foto_principal_index == $key) {
                    $isPrincipal = true;
                } else if ($key === 0 && !$request->has('foto_principal_index')) {
                    $isPrincipal = true; // Define a primeira foto como principal por padrão
                }
                
                ItemFoto::create([
                    'item_id' => $item->id,
                    'caminho' => $path,
                    'ordem' => $key,
                    'is_principal' => $isPrincipal
                ]);
            }
            
            // Registra o número de fotos salvas
            \Log::info('Item ID: ' . $item->id . ' - Total de fotos salvas: ' . $totalFotos);
        }
    
        return redirect()->route('usuario.home')->with('success', 'Item cadastrado com sucesso!');
    }




    public function listarItens() {
        $itens = Item::where('status', 'aprovado')->get();
        return view('listagens.listar-itens',compact('itens') );
    }

    public function listarItensUsuario()
    {
  
        $user = Auth::user();
        $itens = $user->itens;

        return view('usuario.perfil-usuario', compact('user','itens'));
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
        return view('usuario.detalhes-item', compact('item'));
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
