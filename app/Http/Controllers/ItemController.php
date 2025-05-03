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
use App\Notifications\ItemConfirmacaoDevolucaoNotification;

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
        // Verificar se o usuário está ativo
        if (!auth()->user()->ativo) {
            return redirect()->route('usuario.home')->with('error', 'Sua conta está inativa. Não é possível cadastrar itens.');
        }

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
    
        // Definir limites de data (hoje e 2 anos atrás)
        $hoje = Carbon::today();
        $doisAnosAtras = Carbon::today()->subYears(2);
        
        // Validação do item
        $validatedItem = $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'data_perdido' => $request->tipo === 'perdido' ? 'required|date|before_or_equal:today|after_or_equal:'.$doisAnosAtras->format('Y-m-d') : 'nullable|date',
            'data_encontrado' => $request->tipo === 'achado' ? 'required|date|before_or_equal:today|after_or_equal:'.$doisAnosAtras->format('Y-m-d') : 'nullable|date',
        ], [
            'data_perdido.before_or_equal' => 'A data em que o item foi perdido não pode ser uma data futura.',
            'data_perdido.after_or_equal' => 'A data em que o item foi perdido não pode ser anterior a '.$doisAnosAtras->format('d/m/Y').'.',
            'data_encontrado.before_or_equal' => 'A data em que o item foi encontrado não pode ser uma data futura.',
            'data_encontrado.after_or_equal' => 'A data em que o item foi encontrado não pode ser anterior a '.$doisAnosAtras->format('d/m/Y').'.',
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
        $itens = Item::with(['categoria', 'localizacao', 'fotos', 'usuario', 'parceiro'])
            ->whereIn('status', ['aprovado', 'em_estabelecimento'])
            ->whereHas('usuario', function($query) {
                $query->where('ativo', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categorias = Categoria::all();
        
        return view('listagens.listar-itens', compact('itens', 'categorias'));
    }

    public function mapaItens()
    {   
        // Buscar parceiros com todos os dados necessários para o mapa
        $parceiros = Parceiro::with(['localizacao', 'usuario'])
            ->where('status', 'aprovado')
            ->where('ativo', true)
            ->get();
        
        // Buscar itens aprovados e em estabelecimento
        $itens = Item::with(['categoria', 'localizacao', 'fotos', 'parceiro.localizacao'])
            ->whereIn('status', ['aprovado', 'em_estabelecimento'])
            ->whereHas('usuario', function($query) {
                $query->where('ativo', true);
            })
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

    /**
     * Exibe a página de confirmação de devolução
     */
    public function showConfirmacaoDevolucao(Item $item)
    {
        // Verifica permissão: apenas quem devolveu ou admin
        $user = auth()->user();
        if ($item->usuario_devolucao_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('usuario.home')->with('error', 'Você não tem permissão para confirmar esta devolução.');
        }
        // Verifica status
        if ($item->status !== 'devolvido' || $item->devolucao_confirmada) {
            return redirect()->route('usuario.home')->with('info', 'Não há devolução pendente para este item.');
        }
        $usuarioDevolucao = $item->usuarioDevolucao;
        return view('items.confirmacao-devolucao', compact('item', 'usuarioDevolucao'));
    }

    /**
     * Marca um item como devolvido ou recuperado
     */
    public function marcarComoDevolvido(Request $request, Item $item)
    {
        // Log para depuração
        \Log::info('Tentativa de marcar item como devolvido', [
            'item_id' => $item->id,
            'item_tipo' => $item->tipo,
            'item_status' => $item->status,
            'request_data' => $request->all()
        ]);
        
        // Verifica se o usuário é o dono do item
        if ($item->user_id !== auth()->id()) {
            \Log::warning('Permissão negada: usuário não é o dono do item', [
                'item_user_id' => $item->user_id,
                'auth_user_id' => auth()->id()
            ]);
            return redirect()->back()->with('error', 'Você não tem permissão para marcar este item como devolvido.');
        }
        
        // Verifica se o item está em um status que permite devolução
        if (!in_array($item->status, ['aprovado', 'em_estabelecimento'])) {
            \Log::warning('Status do item não permite devolução', [
                'item_status' => $item->status
            ]);
            return redirect()->back()->with('error', 'Este item não pode ser marcado como devolvido no momento.');
        }

        // Regras de validação comuns
        $rules = [
            'metodo_devolucao' => 'required|in:usuario,proprio,parceiro',
            'detalhes' => 'required|string|max:1000'
        ];
        
        // Regras específicas para cada tipo de item
        if ($item->tipo === 'achado') {
            // Para itens achados, o usuário é recomendado mas não obrigatório
            $rules['usuario_id'] = 'nullable|exists:users,id';
        } else {
            // Para itens perdidos, o usuário é opcional (quem devolveu o item)
            $rules['usuario_id'] = 'nullable|exists:users,id';
            $rules['parceiro_id'] = 'nullable|exists:parceiros,id';
        }
        
        // Valida os dados do formulário com tratamento de erro detalhado
        try {
            $validated = $request->validate($rules);
            
            \Log::info('Validação bem-sucedida', [
                'validated_data' => $validated
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erro de validação', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Inicia a transação
        DB::beginTransaction();
        try {
            // Atualiza o item com as informações de devolução
            $item->status = 'devolvido';
            $item->data_devolucao = now();
            $item->observacoes_devolucao = $validated['detalhes'];
            $item->metodo_devolucao = $validated['metodo_devolucao'];
            $item->devolucao_confirmada = true; // Sempre confirmado automaticamente
            $item->data_confirmacao_devolucao = now();
            
            // Registra informações adicionais dependendo do método e tipo do item
            if ($validated['metodo_devolucao'] === 'usuario' && isset($validated['usuario_id'])) {
                $item->usuario_devolucao_id = $validated['usuario_id'];
            } else if ($validated['metodo_devolucao'] === 'parceiro' && isset($validated['parceiro_id'])) {
                $item->parceiro_devolucao_id = $validated['parceiro_id'];
            }
            
            $item->save();
            
            \Log::info('Item marcado como devolvido com sucesso', [
                'item_id' => $item->id,
                'novo_status' => $item->status,
                'metodo_devolucao' => $item->metodo_devolucao
            ]);
            
            // Notifica o dono do item (apenas para registro)
            $user = auth()->user();
            event(new ItemDevolvido($item, $user));
            
            DB::commit();
            
            // Mensagem de sucesso diferente dependendo do tipo de item
            $mensagem = $item->tipo === 'achado' 
                ? 'Item marcado como devolvido com sucesso!' 
                : 'Item marcado como recuperado com sucesso!';
                
            return redirect()->back()->with('success', $mensagem);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao processar devolução', [
                'item_id' => $item->id,
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar a operação: ' . $e->getMessage());
        }
    }
    
    /**
     * Confirma a devolução de um item
     */
    public function confirmarDevolucao(Request $request, Item $item)
    {
        // Verifica permissão: apenas quem devolveu ou admin
        $user = auth()->user();
        if ($item->usuario_devolucao_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('usuario.home')->with('error', 'Você não tem permissão para confirmar esta devolução.');
        }
        
        // Verifica status
        if ($item->status !== 'devolvido' || $item->devolucao_confirmada) {
            return redirect()->route('usuario.home')->with('info', 'Não há devolução pendente para este item.');
        }
        
        // Atualiza o item
        $item->update([
            'devolucao_confirmada' => true,
            'data_confirmacao_devolucao' => now()
        ]);
        
        // Notifica o dono do item
        $donoDevolucao = User::find($item->user_id);
        event(new ItemDevolvido($item, $donoDevolucao));
        
        return redirect()->route('usuario.home')->with('success', 'Devolução confirmada com sucesso!');
    }
    
    /**
     * Recusa a devolução de um item
     */
    public function recusarDevolucao(Request $request, Item $item)
    {
        // Verifica permissão: apenas quem devolveu ou admin
        $user = auth()->user();
        if ($item->usuario_devolucao_id !== $user->id && $user->role !== 'admin') {
            return redirect()->route('usuario.home')->with('error', 'Você não tem permissão para recusar esta devolução.');
        }
        
        // Verifica status
        if ($item->status !== 'devolvido' || $item->devolucao_confirmada) {
            return redirect()->route('usuario.home')->with('info', 'Não há devolução pendente para este item.');
        }
        
        // Reverte o status do item
        $item->update([
            'status' => 'aprovado',
            'usuario_devolucao_id' => null,
            'email_usuario_devolucao' => null,
            'data_devolucao' => null,
            'observacoes_devolucao' => null,
            'metodo_devolucao' => null
        ]);
        
        // Notifica o dono do item
        $donoDevolucao = User::find($item->user_id);
        $donoDevolucao->notify(new \App\Notifications\ItemDevolucaoRecusadaNotification($item));
        
        return redirect()->route('usuario.home')->with('success', 'Devolução recusada com sucesso!');
    }
}