<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Item;
use App\Models\Categoria;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\AdminActionLog;
use App\Models\Parceiro;
use App\Notifications\ItemAprovadoNotification;
use App\Notifications\ItemRejeitadoNotification;
use App\Models\ChMessage;


class AdministradorController extends Controller
{
    private function registrarAcao($item, $acao, $justificativa = null, $statusAnterior = null)
    {
        AdminActionLog::create([
            'admin_id' => auth()->id(),
            'item_id' => $item->id,
            'acao' => $acao,
            'justificativa' => $justificativa,
            'status_anterior' => $statusAnterior,
            'status_novo' => $item->status
        ]);
    }

    /**
     * Aprova um item.
     */
    public function aprovarItem($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        $item = Item::findOrFail($id);
        
        if ($item->status === 'aprovado') {
            return redirect()->back()->with('error', 'Este item já está aprovado.');
        }

        $statusAnterior = $item->status;

        $item->update([
            'status' => 'aprovado',
            'aprovado' => true,
            'aprovado_por_id' => auth()->id(),
            'aprovado_em' => now(),
            'reprovado_por_id' => null,
            'reprovado_em' => null
        ]);

        // Notifica o usuário que registrou o item
        $item->usuario->notify(new ItemAprovadoNotification($item));

        $this->registrarAcao($item, 'aprovacao', null, $statusAnterior);

        return redirect()->back()->with('success', 'Item aprovado com sucesso!');
    }

    /**
     * Rejeita um item.
     */
    public function rejeitarItem(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        $request->validate([
            'justificativa' => 'required|string|min:10'
        ]);

        $item = Item::findOrFail($id);
        $statusAnterior = $item->status;
        
        $item->update([
            'status' => 'reprovado',
            'aprovado' => false,
            'reprovado_por_id' => auth()->id(),
            'reprovado_em' => now(),
            'aprovado_por_id' => null,
            'aprovado_em' => null
        ]);

        // Notifica o usuário que registrou o item
        $item->usuario->notify(new ItemRejeitadoNotification($item, $request->justificativa));

        $this->registrarAcao($item, 'reprovacao', $request->justificativa, $statusAnterior);

        return redirect()->back()->with('success', 'Item rejeitado com sucesso!');
    }

    /**
     * Remove um item.
     */
    public function removerItem($id)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        $item = Item::findOrFail($id);
        
        // Primeiro, excluir os logs relacionados ao item
        AdminActionLog::where('item_id', $item->id)->delete();
        
        // Depois, excluir o item
        $item->delete();

        return redirect()->back()->with('success', 'Item excluído com sucesso!');
    }

    /**
     * Marca um item como devolvido.
     */
    public function marcarComoDevolvido(Item $item)
    {
        $item->update(['status' => 'devolvido']);
        return redirect()->back()->with('success', 'Status atualizado para devolvido.');
    }

    /**
     * Lista itens com filtro de status.
     */
    public function listarItens(Request $request)
    {
        // Obtém o status da requisição (se for nulo ou 'todos', mostra tudo)
        $status = $request->input('status', 'todos');

        // Cria a query base
        $query = Item::query()->with(['categoria', 'usuario', 'fotos']);

        // Aplica o filtro de status
        if ($status !== 'todos') {
            $query->where('status', $status);
        }
        
        // Filtro de busca por texto (descrição ou ID)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('descricao', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        // Filtro por tipo (achado/perdido)
        if ($request->has('tipo') && !empty($request->tipo)) {
            $query->where('tipo', $request->tipo);
        }
        
        // Filtro por categoria
        if ($request->has('categoria') && !empty($request->categoria)) {
            $query->where('id_categoria', $request->categoria);
        }
        
        // Filtro por data de início
        if ($request->has('data_inicio') && !empty($request->data_inicio)) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }
        
        // Filtro por data de fim
        if ($request->has('data_fim') && !empty($request->data_fim)) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Ordenação
        $query->orderBy('created_at', 'desc');

        // Pagina os resultados
        $itens = $query->paginate(12);
        $categorias = Categoria::all();

        return view('admin.listar-all-itens', compact('itens', 'status','categorias'));
    }

    /**
     * Lista todos os itens.
     */
    public function listarItensAll()
    {
        $itens = Item::paginate(10); // 10 itens por página

        return view('admin.listar-itens-user', compact('itens'));
    }

    /**
     * Lista itens aprovados.
     */
    public function listarItensAprovados()
    {
        $itens = Item::where('status', 'aprovado')->get();

        return view('listar.itens.aprovados', compact('itens'));
    }

    /**
     * Lista itens reprovados.
     */
    public function listarItensReprovados()
    {
        $itens = Item::where('status', 'reprovado')->get();
        return view('listar.itens-reprovados', compact('itens'));
    }

    /**
     * Exibe o perfil de um usuário e seus itens.
     */
    public function PerfilUser($id)
    {
        $user = User::findOrFail($id); // Alterado de Usuario para User
        $itens = Item::where('user_id', $id)->get(); // Usando o nome correto da coluna

        return view('admin.listar-itens-usuario', compact('user', 'itens'));
    }

    /**
     * Exclui um item.
     */
    public function excluirItem($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item excluído com sucesso!');
    }

    /**
     * Lista os usuários cadastrados.
     */
    public function listarUsuarios(Request $request)
    {
        $query = User::where('role', 'usuario');

        // Aplica o filtro de busca se houver
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        return view('admin.listar-usuarios', compact('users'));
    }

    /**
     * Exclui um usuário.
     */
    public function excluirUsuario($id)
    {
        $usuario = User::findOrFail($id); // Alterado de Usuario para User
        $usuario->delete();

        return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Exibe o dashboard do administrador.
     */
    public function dashboard()
    {
        $totalUsuarios = User::count(); // Alterado de Usuario para User
        $totalItens = Item::count();
        $itensRecentes = Item::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsuarios', 'totalItens', 'itensRecentes'));
    }

    /**
     * Exibe a página do administrador.
     */
    public function pageAdm()
    {
        return view('admin.dashboard'); // ou o nome correto da view
    }

    /**
     * Cadastra uma nova categoria.
     */
    public function cadastrarCategoria(Request $request)
    {
        $validatedData = $request->validate([
            'nome_categoria' => 'required|string|max:255',
        ]);

        // Cria a categoria
        $categoria = Categoria::create($validatedData);
        return redirect()->route('listar-categorias')->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Lista todas as categorias.
     */
    public function listarCategorias()
    {
        $categorias = Categoria::all();
        return view('listagens.listar-categorias-cadastradas', compact('categorias'));
    }

    /**
     * Exclui uma categoria.
     */
    public function excluirCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect()->route('listar-categorias')->with('success', 'Categoria excluída com sucesso!');
    }

    /**
     * Exibe o formulário para editar uma categoria.
     */
    public function editarCategoria($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('forms.form-categoria', compact('categoria'));
    }

    /**
     * Atualiza uma categoria.
     */
    public function atualizarCategoria(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        return redirect()->route('listar-categorias')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Exibe o formulário para cadastrar uma categoria.
     */
    public function formCategoria()
    {
        return view('forms.form-categoria');
    }

    /**
     * Ativa ou desativa um usuário.
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->ativo = !$user->ativo;
        $user->save();

        return redirect()->back()->with('success', 'Status do usuário atualizado com sucesso!');
    }

    /**
     * Exibe o formulário para cadastrar um novo administrador.
     */
    public function formAdmin()
    {
        return view('admin.form-admin');
    }

    /**
     * Cadastra um novo administrador.
     */
    public function cadastrarAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|max:14|unique:users',
            'telefone' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Cria o usuário com role de admin
        $admin = new User();
        $admin->name = $validatedData['name'];
        $admin->email = $validatedData['email'];
        $admin->password = Hash::make($validatedData['password']);
        $admin->cpf = $validatedData['cpf'];
        $admin->telefone = $validatedData['telefone'];
        $admin->role = 'administrador';
        $admin->ativo = true;

        // Upload da foto se fornecida
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avatars', 'public');
            $admin->avatar = $path;
            $admin->foto = $path; // Salva a mesma foto na coluna foto
        }

        $admin->save();

        return redirect()->route('admin.listar-admins')->with('success', 'Administrador cadastrado com sucesso!');
    }

    /**
     * Lista todos os administradores cadastrados.
     */
    public function listarAdmins()
    {
        $admins = User::where('role', 'administrador')
                     ->orderBy('name')
                     ->paginate(10); // 10 itens por página

        return view('admin.listar-admins', compact('admins'));
    }

    /**
     * Exibe a página de perfil do administrador
     */
    public function perfil()
    {
        return view('admin.perfil-admin');
    }

    /**
     * Atualiza o perfil do administrador
     */
    public function atualizarPerfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|unique:users,cpf,' . $user->id,
            'telefone' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->cpf = $request->cpf;
        $user->telefone = $request->telefone;

        if ($request->hasFile('foto')) {
            // Remove a foto antiga se existir
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            // Salva a nova foto
            $path = $request->file('foto')->store('avatars', 'public');
            $user->avatar = $path;
            $user->foto = $path; // Salva a mesma foto na coluna foto
        }

        $user->save();

        return redirect()->route('admin.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Altera a senha do administrador
     */
    public function alterarSenha(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.perfil')->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Exibe detalhes completos de um item para aprovação/reprovação
     */
    public function verDetalhesItem($id)
    {
        $item = Item::with(['aprovadoPor', 'reprovadoPor', 'excluidoPor'])
            ->findOrFail($id);

        return view('admin.itens.detalhes', compact('item'));
    }

    // Novo método para visualizar o log de ações
    public function logAcoes(Request $request)
    {
        $query = AdminActionLog::with(['admin', 'item']);

        // Aplicar filtro se existir
        if ($request->filled('acao')) {
            $query->where('acao', $request->acao);
        }

        // Ordenar por data mais recente
        $query->orderBy('created_at', 'desc');

        // Paginar resultados
        $logs = $query->paginate(20)->withQueryString();

        // Passar o filtro atual para a view
        $filtroAtual = $request->acao;

        return view('admin.log-acoes', compact('logs', 'filtroAtual'));
    }

    /**
     * Lista todos os parceiros.
     */
    public function listarParceiros(Request $request)
    {
        // Obtém o status da requisição (se for nulo ou 'todos', mostra tudo)
        $status = $request->input('status', 'todos');

        // Cria a query base
        $query = Parceiro::with(['usuario', 'localizacao']);

        // Aplica o filtro de status
        if ($status !== 'todos') {
            $query->where('status', $status);
        }
        
        // Filtro de busca por texto
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome_estabelecimento', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Ordenação
        $query->orderBy('created_at', 'desc');

        // Pagina os resultados
        $parceiros = $query->paginate(10);

        return view('admin.parceiros.index', compact('parceiros', 'status'));
    }

    /**
     * Exibe detalhes de um parceiro específico.
     */
    public function verParceiro(Parceiro $parceiro)
    {
        $parceiro->load(['usuario', 'localizacao', 'aprovadoPor']);
        return view('admin.parceiros.show', compact('parceiro'));
    }

    /**
     * Aprova um parceiro.
     */
    public function aprovarParceiro(Parceiro $parceiro)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        if ($parceiro->status === Parceiro::STATUS_APROVADO) {
            return redirect()->back()->with('error', 'Este parceiro já está aprovado.');
        }

        $parceiro->update([
            'status' => Parceiro::STATUS_APROVADO,
            'ativo' => true,
            'aprovado_por_id' => auth()->id(),
            'data_aprovacao' => now(),
            'motivo_reprovacao' => null
        ]);

        // Enviar notificação por e-mail ao parceiro
        try {
            $parceiro->usuario->notify(new \App\Notifications\ParceiroCadastroAprovadoNotification($parceiro));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação: ' . $e->getMessage());
        }

        return redirect()->route('admin.parceiros.index')
            ->with('success', 'Parceiro aprovado com sucesso!');
    }

    /**
     * Reprova um parceiro.
     */
    public function reprovarParceiro(Request $request, Parceiro $parceiro)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        $request->validate([
            'motivo_reprovacao' => 'required|string|min:10|max:1000'
        ]);

        if ($parceiro->status === Parceiro::STATUS_REPROVADO) {
            return redirect()->back()->with('error', 'Este parceiro já está reprovado.');
        }

        $parceiro->update([
            'status' => Parceiro::STATUS_REPROVADO,
            'ativo' => false,
            'motivo_reprovacao' => $request->motivo_reprovacao
        ]);

        // Enviar notificação por e-mail ao parceiro
        try {
            $parceiro->usuario->notify(new \App\Notifications\ParceiroCadastroReprovadoNotification($parceiro));
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação: ' . $e->getMessage());
        }

        return redirect()->route('admin.parceiros.index')
            ->with('success', 'Parceiro reprovado com sucesso.');
    }

    /**
     * Ativa ou desativa um parceiro aprovado.
     */
    public function desativarParceiro(Request $request, Parceiro $parceiro)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Acesso não autorizado.');
        }

        if ($parceiro->status !== Parceiro::STATUS_APROVADO) {
            return redirect()->back()->with('error', 'Apenas parceiros aprovados podem ser ativados/desativados.');
        }

        // Se estiver ativando, apenas muda o status
        if (!$parceiro->ativo) {
            $parceiro->update([
                'ativo' => true,
                'motivo_inativacao' => null
            ]);
            $status = 'ativado';
        } else {
            // Se estiver desativando, requer motivo
            $request->validate([
                'motivo_inativacao' => 'required|string|min:10|max:500'
            ]);

            $parceiro->update([
                'ativo' => false,
                'motivo_inativacao' => $request->motivo_inativacao
            ]);
            $status = 'desativado';
        }

        return redirect()->route('admin.parceiros.show', $parceiro)
            ->with('success', "Parceiro {$status} com sucesso.");
    }

    /**
     * Lista os itens de um parceiro específico.
     */
    public function listarItensParceiro(Parceiro $parceiro)
    {
        $itens = Item::where('parceiro_id', $parceiro->id)
            ->with(['categoria', 'usuario', 'fotos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.parceiros.itens', compact('parceiro', 'itens'));
    }



 

    public function desativar(Parceiro $parceiro)
    {
        $parceiro->ativo = !$parceiro->ativo;
        $parceiro->save();

        $message = $parceiro->ativo ? 'Parceiro ativado com sucesso!' : 'Parceiro desativado com sucesso!';
        return redirect()->back()->with('success', $message);
    }

    public function destroy(Parceiro $parceiro)
    {
        // Primeiro excluir todos os itens relacionados
        $parceiro->itens()->delete();
        
        // Excluir todas as mensagens do chat
        ChMessage::where('from_id', $parceiro->usuario->id)
                ->orWhere('to_id', $parceiro->usuario->id)
                ->delete();
        
        // Excluir o usuário associado
        $parceiro->usuario->delete();
        
        // Por fim, excluir o parceiro
        $parceiro->delete();

        return redirect()->route('admin.parceiros.index')->with('success', 'Parceiro excluído com sucesso!');
    }

}