<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Item;

class AdministradorController extends Controller
{
   

    // Função para listar itens pendentes ou impróprios
  // Listar itens pendentes com os usuários associados
  public function listarItens()
  {
      $itens = Item::where('status', 'pendente')
          ->with('usuario') // Carrega o relacionamento com usuários
          ->get();

      return view('admin.listar-itens-pendentes', compact('itens'));
  }

  // Listar todos os itens cadastrados por um usuário específico
  public function listarItensPorUsuario($id)
  {
      $usuario = Usuario::findOrFail($id); // Verifica se o usuário existe
      $itens = Item::where('id_usuario', $id)->get(); // Busca itens associados ao usuário

      return view('admin.listar-itens-usuario', compact('usuario', 'itens'));
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
