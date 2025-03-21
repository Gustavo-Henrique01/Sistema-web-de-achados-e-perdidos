<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\User;
use App\Models\Localizacao;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParceiroController extends Controller
{
    /**
     * Mostra a página inicial do parceiro.
     */
    public function home()
    {
        $parceiro = Auth::user()->parceiro;
        $itens = $parceiro->itens;
        
        return view('parceiro.home', compact('parceiro', 'itens'));
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
        return view('admin.parceiros.create');
    }

    /**
     * Armazena um novo parceiro no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados do usuário
        $validatedUserData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|max:15',
            'senha' => 'required|string|min:6',
            'cpf' => 'required|string|unique:users,cpf|size:11',
        ]);

        // Validação dos dados de localização
        $validatedLocalizacaoData = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'referencia' => 'nullable|string',
        ]);

        // Validação dos dados do parceiro
        $validatedParceiroData = $request->validate([
            'nome_estabelecimento' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'horario_funcionamento' => 'nullable|string',
            'telefone_comercial' => 'nullable|string|max:15',
            'tipo_parceiro' => 'required|in:ponto_coleta,evento,ambos',
            'data_inicio_parceria' => 'required|date',
        ]);

        // Upload de logo se fornecido
        if ($request->hasFile('logo')) {
            $validatedParceiroData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        DB::beginTransaction();
        
        try {
            // Criar usuário com papel de parceiro
            $user = User::create([
                'name' => $validatedUserData['name'],
                'email' => $validatedUserData['email'],
                'telefone' => $validatedUserData['telefone'],
                'senha' => $validatedUserData['senha'],
                'cpf' => $validatedUserData['cpf'],
                'role' => UserRole::PARCEIRO,
                'ativo' => true,
            ]);

            // Criar localização
            $localizacao = Localizacao::create($validatedLocalizacaoData);

            // Criar parceiro
            $parceiro = Parceiro::create([
                'user_id' => $user->id,
                'id_localizacao' => $localizacao->id,
                'nome_estabelecimento' => $validatedParceiroData['nome_estabelecimento'],
                'descricao' => $validatedParceiroData['descricao'],
                'horario_funcionamento' => $validatedParceiroData['horario_funcionamento'],
                'telefone_comercial' => $validatedParceiroData['telefone_comercial'],
                'logo' => $validatedParceiroData['logo'] ?? null,
                'tipo_parceiro' => $validatedParceiroData['tipo_parceiro'],
                'data_inicio_parceria' => $validatedParceiroData['data_inicio_parceria'],
                'ativo' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.parceiros.index')
                ->with('success', 'Parceiro cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Remover imagem se houve upload
            if (isset($validatedParceiroData['logo'])) {
                Storage::disk('public')->delete($validatedParceiroData['logo']);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar parceiro: ' . $e->getMessage()]);
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
            'tipo_parceiro' => 'required|in:ponto_coleta,evento,ambos',
            'data_inicio_parceria' => 'required|date',
            'ativo' => 'boolean',
        ]);

        // Validação dos dados de localização
        $validatedLocalizacaoData = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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
    public function listarItens(Parceiro $parceiro)
    {
        $itens = $parceiro->itens()->paginate(10);
        
        return view('parceiros.itens', compact('parceiro', 'itens'));
    }

    /**
     * Exibe formulário para um parceiro vincular um item existente.
     */
    public function vincularItemForm()
    {
        $parceiro = Auth::user()->parceiro;
        $itens = Item::where('status', 'aprovado')
                    ->whereNull('parceiro_id')
                    ->get();
                    
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
     * Desvincula um item de um parceiro.
     */
    public function desvincularItem(Item $item)
    {
        $parceiro = Auth::user()->parceiro;
        
        // Verificar se o item pertence a este parceiro
        if ($item->parceiro_id !== $parceiro->id) {
            return redirect()->back()->withErrors(['error' => 'Este item não está vinculado ao seu estabelecimento.']);
        }
        
        // Desvincular item
        $item->update(['parceiro_id' => null]);
        
        return redirect()->route('parceiro.itens')
            ->with('success', 'Item desvinculado do seu estabelecimento com sucesso!');
    }
} 