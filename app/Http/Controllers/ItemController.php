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
use App\Models\Parceiro;
use App\Models\ItemTransferencia;
use App\Notifications\ItemTransferidoNotification;
use App\Notifications\ItemEnviadoParaParceiroNotification;
use Illuminate\Support\Facades\DB;
use App\Events\ItemRejeitado;
use App\Events\ItemDevolvido;
use App\Events\ItemParceiroStatusChanged;

class ItemController extends Controller
{
    private function verificarPerfilCompleto(User $user)
    {
        $camposFaltantes = [];
        
        if (empty($user->foto)) {
            $camposFaltantes[] = 'foto';
        }
        
        if (empty($user->telefone)) {
            $camposFaltantes[] = 'telefone';
        }
        
        if (empty($user->cpf)) {
            $camposFaltantes[] = 'CPF';
        }
        
        return $camposFaltantes;
    }

    public function index()
    {
        $user = Auth::user();
        $camposFaltantes = $this->verificarPerfilCompleto($user);
        
        if (!empty($camposFaltantes)) {
            $mensagem = 'Para cadastrar um item, você precisa completar seu perfil com as seguintes informações: ' . 
                       implode(', ', $camposFaltantes) . '.';
            
            return redirect()
                ->route('perfil-usuario')
                ->with('warning', $mensagem);
        }
        
        $categorias = Categoria::all();
        return view('forms.form-registroItem', compact('categorias'));
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
    
        return redirect()->route('usuario.home')->with('success', 'Item cadastrado com sucesso! A aprovação pode levar até 5 dias úteis.');
    }




    public function listarItens()
    {
        $itens = Item::with(['categoria', 'localizacao', 'fotos'])
            ->where('status', 'aprovado')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categorias = Categoria::all();
        
        return view('listagens.listar-itens', compact('itens', 'categorias'));
    }

    public function mapaItens()
    {   
        $parceiros = Parceiro::all();
        $itens = Item::with(['categoria', 'localizacao', 'fotos'])
            ->where('status', 'aprovado')
            ->get();
            
        $categorias = Categoria::all();
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        
        return view('listagens.mapa-itens', [
            'itens' => $itens,
            'categorias' => $categorias,
            'parceiros' => $parceiros,
            'googleMapsApiKey' => $googleMapsApiKey
        ]);
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
        // Carrega os relacionamentos necessários para a página de detalhes
        $item->load(['categoria', 'localizacao', 'fotos', 'usuario', 'parceiro.localizacao']);
        
        return view('listagens.detalhes-item', compact('item'));
    }

    public function showParceiro(Item $item)
    {
        // Carrega os relacionamentos necessários para a página de detalhes
        $item->load(['categoria', 'localizacao', 'fotos', 'usuario', 'parceiro.localizacao']);
        
        // Verifica se o item pertence ao parceiro logado
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return redirect()->route('parceiro.itens')
                ->with('error', 'Você não tem permissão para visualizar este item.');
        }
        
        return view('parceiro.detalhes-item', compact('item'));
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

    public function enviarParaParceiro(Request $request, Item $item)
    {
        // Verifica se o usuário tem permissão
        if ($item->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para enviar este item.');
        }

        // Verifica se o item está aprovado
        if ($item->status !== 'aprovado') {
            return redirect()->back()->with('error', 'Este item não está aprovado para transferência.');
        }

        // Valida os dados
        $validated = $request->validate([
            'parceiro_id' => 'required|exists:parceiros,id',
            'observacoes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Verifica se o parceiro está ativo
            $parceiro = Parceiro::findOrFail($validated['parceiro_id']);
            \Log::info('Parceiro encontrado', ['parceiro' => $parceiro]);

            if (!$parceiro->ativo) {
                throw new \Exception('Este parceiro não está ativo no momento.');
            }

            // Cria a transferência
            $transferencia = ItemTransferencia::create([
                'item_id' => $item->id,
                'parceiro_id' => $validated['parceiro_id'],
                'usuario_id' => auth()->id(),
                'observacoes' => $validated['observacoes'],
                'status' => 'pendente'
            ]);

            \Log::info('Transferência criada', ['transferencia' => $transferencia]);

            // Atualiza o status do item
            $item->update([
                'status' => 'em_transferencia',
                'parceiro_id' => $validated['parceiro_id']
            ]);

            \Log::info('Status do item atualizado', ['item' => $item]);

            DB::commit();
            \Log::info('Transação concluída com sucesso');

            return redirect()->route('usuario.perfil')
                ->with('success', 'Item enviado para o ponto de coleta com sucesso! Aguarde a confirmação do recebimento.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro durante a transação', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao enviar o item: ' . $e->getMessage());
        }
    }

    public function enviarParaParceiroForm(Item $item)
    {
        // Verifica se o usuário atual é o dono do item
        if ($item->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para enviar este item.');
        }

        // Verifica se o item está aprovado
        if ($item->status !== 'aprovado') {
            return redirect()->back()->with('error', 'Este item precisa estar aprovado para ser enviado a um parceiro.');
        }

        // Verifica se o item já está em transferência
        if ($item->status === 'em_transferencia') {
            return redirect()->back()->with('error', 'Este item já está em processo de transferência.');
        }

        // Busca todos os parceiros ativos
        $parceiros = Parceiro::with('localizacao')
            ->where('ativo', true)
            ->get();

        return view('usuario.enviar-para-parceiro', compact('item', 'parceiros'));
    }

    public function rejeitarItem(Item $item, Request $request)
    {
        // ... existing code ...
        
        event(new ItemRejeitado($item, $item->user, $request->motivo));
        
        // ... existing code ...
    }

    public function devolverItem(Item $item)
    {
        // ... existing code ...
        
        event(new ItemDevolvido($item, $item->user));
        
        // ... existing code ...
    }

    public function vincularParceiro(Item $item, Parceiro $parceiro, Request $request)
    {
        // ... existing code ...
        
        event(new ItemParceiroStatusChanged($item, $parceiro, $item->user, $request->status));
        
        // ... existing code ...
    }
}
