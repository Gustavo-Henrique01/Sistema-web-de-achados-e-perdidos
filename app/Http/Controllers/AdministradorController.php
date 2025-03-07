<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
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

    public function listarItens(Request $request) {
        // Obtém o status da requisição (se for nulo ou 'todos', mostra tudo)
        $status = $request->input('status', 'todos');
    
        // Cria a query base
        $query = Item::query();
    
        // Aplica o filtro apenas se o status for diferente de 'todos'
        if ($status !== 'todos') {
            $query->where('status', $status);
        }
    
        // Pagina os resultados
        $itens = $query->paginate(10);
    
        return view('admin.listar-all-itens', compact('itens','status'));
    }
    

    /**
     * Lista itens pendentes.
     */
    public function listarItensAll()
    {
        $itens = Item::paginate(10); // 10 itens por página

        return view('admin.listar-itens-user', compact('itens'));
    }
     
    

  /**
     * Lista itens pendentes.
     */
     
    public function listarItensAprovados() {

        $itens = Item::where('status', 'aprovado');
    
        return view('listar.itens.reprovados ', compact('itens'));
    }

    public   function listarItensReprovados() {

        $itens = Item::where('status', 'reprovado');
        return view('listar.itens-reprovados');
    }
    

   
    public function PerfilUser($id)
    {
        $usuario = Usuario::findOrFail($id);
        $itens = Item::where('id_usuario', $id)->get();

        return view('admin.listar-itens-usuario', compact('usuario', 'itens'));
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
        $usuarios = Usuario::where('role', 'usuario')->get();
        return view('admin.listar-usuarios', compact('usuarios'));
    }

    /**
     * Exclui um usuário.
     */
    public function excluirUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
    }

    
    
    public function dashboard()
    {
        $totalUsuarios = Usuario::count();
        $totalItens = Item::count();
        $itensRecentes = Item::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsuarios', 'totalItens', 'itensRecentes'));
    }


    public function pageAdm () {
        return view('admin.dashboard'); // ou o nome correto da view
    }


    public function cadastrarCategoria(Request $request) {
        $validatedData = $request->validate([
            'nome_categoria' => 'required|string|max:255',

        ]);

        // Cria a categoria
        $categoria = Categoria::create($validatedData);
        return redirect()->route('listar-categoria')->with('success', 'Categoria cadastrada com sucesso!');
    }

    public function listarCategorias() {
        $categorias = Categoria::all();
        return view('listagens.listar-categorias-cadastradas', compact('categorias'));

    }
    
    public function excluirCategoria($id) {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return redirect()->route('listar-categorias')->with('success', 'Categoria excluída com sucesso!');
    }
    public function editarCategoria($id) {
        $categoria = Categoria::findOrFail($id);
        return view('forms.form-categoria', compact('categoria'));
    }
    public function atualizarCategoria(Request $request, $id) {
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        return redirect()->route('listar-categorias')->with('success', 'Categoria atualizada com sucesso!');
    }
    public function formCategoria() {
        return view('forms.form-categoria');
    }
}
