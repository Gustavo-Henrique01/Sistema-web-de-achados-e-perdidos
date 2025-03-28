<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Alterado de Usuario para User
use App\Models\Item;
use App\Models\Categoria;

class AdministradorController extends Controller
{
    /**
     * Aprova um item.
     */
    public function aprovarItem($id)
    {
        $item = Item::findOrFail($id);
        $item->update([
            'status' => 'aprovado',
            'aprovado' => true,
            'aprovado_em' => now(),
        ]);

        return redirect()->back()->with('success', 'Item aprovado com sucesso!');
    }

    /**
     * Rejeita um item.
     */
    public function rejeitarItem($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'reprovado']);

        return redirect()->back()->with('warning', 'Item rejeitado.');
    }

    /**
     * Remove um item.
     */
    public function removerItem($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.listar-itens');
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
            $query->where('categoria_id', $request->categoria);
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

        return view('admin.listar-all-itens', compact('itens', 'status'));
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
    public function listarUsuarios()
    {
        $usuarios = User::where('role', 'usuario')->get(); // Alterado de Usuario para User
        return view('admin.listar-usuarios', compact('usuarios'));
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
        return redirect()->route('listar-categoria')->with('success', 'Categoria cadastrada com sucesso!');
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
     * Obtém os detalhes de um item específico para exibição no modal.
     */
    public function getItemDetails($id)
    {
        $item = Item::with(['categoria', 'usuario', 'fotos', 'localizacao'])
                   ->findOrFail($id);
        
        return view('admin.partials.item-details-modal', compact('item'));
    }
}