<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\User;
use App\Models\Localizacao;
use App\Models\UserRole;
use App\Models\Item;
use App\Models\ItemTransferencia;
use App\Notifications\ItemRecebidoNotification;
use App\Notifications\ItemRejeitadoNotification;
use App\Notifications\ItemDevolvidoNotification;
use App\Notifications\ItemConfirmadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ParceiroController extends Controller
{
    /**
     * Mostra a página inicial do parceiro.
     */
    public function home()
    {
        $parceiro = Auth::user()->parceiro;
        $itens = $parceiro->itens;
        
        return view('parceiro.dashboard', compact('parceiro', 'itens'));
    }

    /**
     * Exibe listagem de parceiros.
     */
    public function index()
    {
        // Apenas administradores podem ver todos os parceiros
        $parceiros = Parceiro::with(['usuario', 'localizacao'])->get();
        return view('admin.parceiros.index', compact('parceiros'));
    }

    /**
     * Exibe formulário para criação de parceiro.
     */
    public function create()
    {
        return view('forms.form-parceiro');
    }

    /**
     * Armazena um novo parceiro no banco de dados.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_estabelecimento' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:parceiros,cnpj',
            'tipo_parceiro' => 'required|in:ponto_coleta,evento,ambos',
        ], [
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
            'cnpj.max' => 'O CNPJ deve ter no máximo 18 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Log dos dados recebidos
            \Log::info('Dados recebidos no cadastro de parceiro:', $request->all());

            // Validação dos dados do usuário
            $validatedUserData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'telefone' => 'required|string|max:15',
                'senha' => 'required|string|min:6|confirmed',
                'cpf' => 'required|string|unique:users,cpf|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
            ]);

            \Log::info('Dados do usuário validados com sucesso');

            // Validação dos dados de localização
            $validatedLocalizacaoData = $request->validate([
                'nome_local' => 'required|string|max:255',
                'endereco' => 'required|string|max:255',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'referencia' => 'nullable|string',
            ]);

            \Log::info('Dados de localização validados com sucesso');

            // Validação dos dados do parceiro
            $validatedParceiroData = $request->validate([
                'nome_estabelecimento' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'horario_funcionamento' => 'nullable|string',
                'telefone_comercial' => 'nullable|string|max:15',
                'tipo_parceiro' => 'required|string|in:ponto_coleta,evento,ambos',
            ]);

            \Log::info('Dados do parceiro validados com sucesso');

            // Upload de logo se fornecido
            if ($request->hasFile('logo')) {
                $validatedParceiroData['logo'] = $request->file('logo')->store('logos', 'public');
                \Log::info('Logo enviada com sucesso: ' . $validatedParceiroData['logo']);
            }

            DB::beginTransaction();
            
            try {
                \Log::info('Iniciando criação do usuário com os dados:', $validatedUserData);
                
                // Criar usuário com papel de parceiro
                $user = User::create([
                    'name' => $validatedUserData['name'],
                    'email' => $validatedUserData['email'],
                    'telefone' => $validatedUserData['telefone'],
                    'senha' => Hash::make($validatedUserData['senha']), // Hash explícito da senha
                    'cpf' => $validatedUserData['cpf'],
                    'role' => UserRole::PARCEIRO->value,
                    'ativo' => true, // O usuário está ativo, mas o parceiro não estará até ser aprovado
                ]);

                \Log::info('Usuário criado com sucesso:', ['user_id' => $user->id]);

                \Log::info('Iniciando criação da localização com os dados:', $validatedLocalizacaoData);
                
                // Criar localização
                $localizacao = Localizacao::create($validatedLocalizacaoData);

                \Log::info('Localização criada com sucesso:', ['localizacao_id' => $localizacao->id]);

                \Log::info('Iniciando criação do parceiro com os dados:', $validatedParceiroData);
                
                // Criar parceiro - Agora com status pendente e inativo até aprovação
                $parceiro = Parceiro::create([
                    'user_id' => $user->id,
                    'id_localizacao' => $localizacao->id,
                    'nome_estabelecimento' => $validatedParceiroData['nome_estabelecimento'],
                    'descricao' => $validatedParceiroData['descricao'],
                    'horario_funcionamento' => $validatedParceiroData['horario_funcionamento'],
                    'telefone_comercial' => $validatedParceiroData['telefone_comercial'],
                    'logo' => $validatedParceiroData['logo'] ?? null,
                    'tipo_parceiro' => $validatedParceiroData['tipo_parceiro'],
                    'data_inicio_parceria' => now(),
                    'ativo' => false, // Inativo até aprovação
                    'status' => Parceiro::STATUS_PENDENTE, // Status pendente
                    'cnpj' => $request->cnpj,
                ]);

                \Log::info('Parceiro criado com sucesso:', ['parceiro_id' => $parceiro->id]);

                DB::commit();
                \Log::info('Transação concluída com sucesso');

                return redirect()->route('login')
                    ->with('success', 'Cadastro de parceiro enviado com sucesso! Nossa equipe irá analisar suas informações em até 3 dias úteis. Fique atento ao seu e-mail para mais informações.');
            } catch (\Exception $e) {
                DB::rollBack();
                
                \Log::error('Erro ao criar parceiro:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Remover imagem se houve upload
                if (isset($validatedParceiroData['logo'])) {
                    Storage::disk('public')->delete($validatedParceiroData['logo']);
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Erro ao cadastrar parceiro: ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            \Log::error('Erro na validação:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro na validação dos dados: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibe detalhes de um parceiro específico.
     */
    public function show(Parceiro $parceiro)
    {
        $parceiro->load(['usuario', 'localizacao', 'itens']);
        return view('admin.parceiros.show', compact('parceiro'));
    }

    /**
     * Exibe formulário para edição de parceiro.
     */
    public function edit(Parceiro $parceiro)
    {
        return view('admin.parceiros.edit', compact('parceiro'));
    }

    /**
     * Atualiza um parceiro específico.
     */
    public function update(Request $request, Parceiro $parceiro)
    {
        // Validação dos dados do parceiro
        $validatedParceiroData = $request->validate([
            'nome_estabelecimento' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'horario_funcionamento' => 'nullable|string',
            'telefone_comercial' => 'nullable|string|max:15',
            'tipo_parceiro' => 'required|string|in:ponto_coleta,evento,ambos',
            'data_inicio_parceria' => 'required|date',
            'ativo' => 'boolean',
        ]);

        // Validação dos dados de localização
        $validatedLocalizacaoData = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'referencia' => 'nullable|string',
        ]);

        // Upload de logo se fornecido
        if ($request->hasFile('logo')) {
            // Remover logo anterior se existir
            if ($parceiro->logo) {
                Storage::disk('public')->delete($parceiro->logo);
            }
            
            $validatedParceiroData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        DB::beginTransaction();
        
        try {
            // Atualizar parceiro
            $parceiro->update($validatedParceiroData);

            // Atualizar localização
            $parceiro->localizacao()->update($validatedLocalizacaoData);
            
            DB::commit();

            return redirect()->route('admin.parceiros.index')
                ->with('success', 'Parceiro atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Remover imagem se houve upload
            if (isset($validatedParceiroData['logo']) && $validatedParceiroData['logo'] != $parceiro->getOriginal('logo')) {
                Storage::disk('public')->delete($validatedParceiroData['logo']);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar parceiro: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove um parceiro específico.
     */
    public function destroy(Parceiro $parceiro)
    {
        try {
            // Remover logo se existir
            if ($parceiro->logo) {
                Storage::disk('public')->delete($parceiro->logo);
            }
            
            // Verificamos se devemos excluir o usuário também
            $user = $parceiro->usuario;
            
            // Excluir parceiro
            $parceiro->delete();
            
            // Excluir usuário associado (soft delete)
            $user->delete();
            
            return redirect()->route('admin.parceiros.index')
                ->with('success', 'Parceiro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao excluir parceiro: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostra todos os parceiros em um mapa.
     */
    public function mapa()
    {
        $parceiros = Parceiro::with('localizacao')->ativo()->get();
        
        return view('parceiros.mapa', compact('parceiros'));
    }

    /**
     * Lista itens associados a um parceiro.
     */
    public function listarItens(Request $request)
    {
        $parceiro = Auth::user()->parceiro;
        
        $query = $parceiro->itens();
        
        // Aplicar filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('descricao', 'like', "%{$search}%");
            });
        }
        
        $itens = $query->latest()->paginate(10);
        
        return view('parceiro.itens', compact('parceiro', 'itens'));
    }

    /**
     * Exibe formulário para um parceiro vincular um item existente.
     */
    public function vincularItemForm()
    {
        $parceiro = Auth::user()->parceiro;
        
        // Buscar itens disponíveis para vinculação
        $itens = Item::where('status', 'pendente')
                     ->whereNull('parceiro_id')
                     ->latest()
                     ->paginate(10);
        
        return view('parceiro.vincular-item', compact('parceiro', 'itens'));
    }

    /**
     * Vincula um item existente a um parceiro.
     */
    public function vincularItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:itens,id',
        ]);
        
        $parceiro = Auth::user()->parceiro;
        $item = Item::findOrFail($validated['item_id']);
        
        // Verificar se o item já está vinculado a outro parceiro
        if ($item->parceiro_id !== null && $item->parceiro_id !== $parceiro->id) {
            return redirect()->back()->withErrors(['error' => 'Este item já está vinculado a outro parceiro.']);
        }
        
        // Vincular item ao parceiro
        $item->update(['parceiro_id' => $parceiro->id]);
        
        return redirect()->route('parceiro.itens')
            ->with('success', 'Item vinculado ao seu estabelecimento com sucesso!');
    }

    /**
     * Desvincula um item do parceiro.
     */
    public function desvincularItem(Request $request, Item $item)
    {
        try {
            // Verifica se o item pertence ao parceiro
            if ($item->parceiro_id !== auth()->user()->parceiro->id) {
                return redirect()->back()
                    ->withErrors(['error' => 'Este item não pertence ao seu estabelecimento.']);
            }

            // Desvincula o item
            $item->update([
                'parceiro_id' => null,
                'status' => 'pendente'
            ]);

            return redirect()->route('parceiro.itens')
                ->with('success', 'Item desvinculado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao desvincular item: ' . $e->getMessage()]);
        }
    }

    public function confirmarRecebimento(Request $request, Item $item)
    {
        if ($item->status !== Item::STATUS_EM_TRANSFERENCIA) {
            return back()->with('error', 'Este item não está em transferência.');
        }

        // Verifica se o item está vinculado ao parceiro atual
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return back()->with('error', 'Este item não está vinculado ao seu estabelecimento.');
        }

        DB::beginTransaction();
        try {
            // Atualiza o status do item
            $item->update([
                'status' => Item::STATUS_EM_ESTABELECIMENTO,
                'parceiro_id' => auth()->user()->parceiro->id
            ]);

            // Atualiza a transferência
            $transferencia = ItemTransferencia::where('item_id', $item->id)
                ->where('parceiro_id', auth()->user()->parceiro->id)
                ->where('status', 'pendente')
                ->firstOrFail();

            $transferencia->update([
                'status' => 'confirmada',
                'data_confirmacao' => now()
            ]);

            // Notifica o usuário que registrou o item
            $item->usuario->notify(new ItemConfirmadoNotification($item, auth()->user()->parceiro));

            DB::commit();
            return back()->with('success', 'Item recebido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao confirmar recebimento do item: ' . $e->getMessage());
        }
    }

    public function rejeitarRecebimento(Request $request, Item $item)
    {
        if ($item->status !== Item::STATUS_EM_TRANSFERENCIA) {
            return back()->with('error', 'Este item não está em transferência.');
        }

        // Verifica se o item está vinculado ao parceiro atual
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return back()->with('error', 'Este item não está vinculado ao seu estabelecimento.');
        }

        $request->validate([
            'motivo' => 'required|string|min:10|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Atualiza o status do item
            $item->update([
                'status' => Item::STATUS_APROVADO,
                'parceiro_id' => null
            ]);

            // Atualiza a transferência
            $transferencia = ItemTransferencia::where('item_id', $item->id)
                ->where('parceiro_id', auth()->user()->parceiro->id)
                ->where('status', 'pendente')
                ->firstOrFail();

            $transferencia->update([
                'status' => 'rejeitada',
                'observacoes' => $request->motivo
            ]);

            // Notifica o usuário que registrou o item
            $item->usuario->notify(new ItemRejeitadoNotification($item, auth()->user()->parceiro, $request->motivo));

            DB::commit();
            return back()->with('success', 'Item rejeitado com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao rejeitar o item: ' . $e->getMessage());
        }
    }

    public function marcarDevolvido(Request $request, Item $item)
    {
        try {
            DB::beginTransaction();

            // Verifica se o item está no estabelecimento deste parceiro
            if ($item->status !== Item::STATUS_EM_ESTABELECIMENTO || $item->parceiro_id !== auth()->user()->parceiro->id) {
                throw new \Exception('Item não está disponível para devolução.');
            }

            // Validação das observações
            $request->validate([
                'observacoes' => 'required|string|min:10|max:500'
            ]);

            // Atualiza o status do item para 'devolvido'
            $item->update([
                'status' => Item::STATUS_DEVOLVIDO,
                'data_devolucao' => now(),
                'observacoes_devolucao' => $request->observacoes
            ]);

            // Notifica o usuário
            $item->usuario->notify(new ItemDevolvidoNotification($item, $request->observacoes));

            DB::commit();

            return redirect()->route('parceiro.itens.show', $item)
                ->with('success', 'Item marcado como devolvido com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao marcar item como devolvido: ' . $e->getMessage());
        }
    }

    /**
     * Lista as transferências pendentes para o parceiro.
     */
    public function transferenciasPendentes()
    {
        $parceiro = auth()->user()->parceiro;
        $transferencias = ItemTransferencia::with(['item', 'usuario', 'item.categoria'])
            ->where('parceiro_id', $parceiro->id)
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('parceiro.transferencias-pendentes', compact('transferencias'));
    }

    /**
     * Exibe a página de aguardando aprovação.
     */
    public function aguardandoAprovacao()
    {
        $parceiro = auth()->user()->parceiro;
        
        if (!$parceiro) {
            return redirect()->route('parceiro.cadastro');
        }
        
        if ($parceiro->status === Parceiro::STATUS_APROVADO) {
            return redirect()->route('parceiro.home');
        }
        
        return view('parceiro.aguardando-aprovacao', compact('parceiro'));
    }
} 