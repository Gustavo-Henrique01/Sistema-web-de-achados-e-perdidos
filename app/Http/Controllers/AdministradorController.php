<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Item;

class AdministradorController extends Controller
{
    // Middleware para autenticação e verificação de administrador
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'administrador') {
                abort(403, 'Acesso negado. Você não tem permissões de administrador.');
            }
            return $next($request);
        });
    }

    // Função para listar itens pendentes ou impróprios
    public function listarItens()
    {
        $itens = Item::where('status', 'pendente')->get(); // Itens com status 'pendente'

        return view('admin.listar-itens', compact('itens'));
    }

    // Função para excluir itens impróprios
    public function excluirItem($id)
    {
        $item = Item::findOrFail($id);

        // Exclui o item do banco de dados
        $item->delete();

        return redirect()->back()->with('success', 'Item excluído com sucesso!');
    }

    // Função para listar usuários cadastrados
    public function listarUsuarios()
    {
        $usuarios = Usuario::where('role', 'usuario')->get(); // Apenas usuários comuns

        return view('admin.listar-usuarios', compact('usuarios'));
    }

    // Função para excluir usuários impróprios
    public function excluirUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);

        $usuario->delete();

        return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
    }
}
