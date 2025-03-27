<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Localizacao;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function home()
    {
        return view('usuario.home');
    }

    public function index()
    {
        return view('forms.form-registro');
    }

    public function showLogin()
    {
        return view('Auth.login');
    }

    public function criarUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Tabela 'users'
            'telefone' => 'required|string|max:15',
            'senha' => 'required|string|min:5', // Campo 'senha'
            'foto' => 'nullable|string',
            'cpf' => 'required|string|unique:users,cpf|size:11', // Tabela 'users'
        ]);

        $validatedData['role'] = 'usuario';
        $validatedData['ativo'] = true;

        // O Eloquent já faz o hash da senha automaticamente (graças ao cast 'hashed' no model)
        $usuario = User::create($validatedData);
        
      

        return redirect()->route('form.login');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function listarItens()
    {
        $user = Auth::user();

        // Obtém todos os itens do usuário logado
        $itens = $user->itens; // Acessa os itens do usuário logado

        // Retorna a view com os itens
        return view('listagens.itens-cadastrados-usuario', compact('itens'));
    }

    public function editarItem($id)
    {
        // Busca o item pelo ID
        $item = Item::find($id);
        $categorias = Categoria::all();

        // Verifica se o item existe
        if (!$item) {
            return redirect()->route('usuario.home')->with('error', 'Item não encontrado.');
        }

        // Verifica se o item pertence ao usuário autenticado (opcional, mas recomendado)
        if ($item->user_id !== auth()->id()) {
            return redirect()->route('usuario.home')->with('error', 'Você não tem permissão para editar este item.');
        }

        // Busca o endereço associado ao item
        $localizacao= $item->localizacao;

        // Retorna a view com os dados do item e do endereço
        return view('forms.form-registroitem', compact('item', 'localizacao','categorias'));
    }

    public function atualizarItem(Request $request, $id)
    {
        // Busca o item pelo ID
        $item = Item::find($id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item não encontrado.');
        }
    
        // Validação da localização
        $validatedLocalizacao = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255', 
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'referencia' => 'required|string|max:1000',
        ]);
    
        // Atualiza a localização associada ao item
        $localizacao = Localizacao::find($item->id_localizacao);
        if ($localizacao) {
            $localizacao->update($validatedLocalizacao);
        } else {
            return redirect()->back()->with('error', 'Localização não encontrada.');
        }
    
        // Validação do item
        $validatedItem = $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Foto é opcional
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'data_perdido' => $request->tipo === 'perdido' ? 'required|date' : 'nullable|date',
            'data_encontrado' => $request->tipo === 'achado' ? 'required|date' : 'nullable|date',
        ]);
    
        // Atualiza a foto, se fornecida
        if ($request->hasFile('foto')) {
            // Remove a foto antiga, se existir
            if ($item->foto && Storage::disk('public')->exists($item->foto)) {
                Storage::disk('public')->delete($item->foto);
            }
            // Armazena a nova foto
            $validatedItem['foto'] = $request->file('foto')->store('imagens', 'public');
        } else {
            // Mantém a foto atual se nenhuma nova foto for enviada
            $validatedItem['foto'] = $item->foto;
        }
    
      
        $item->update($validatedItem);
    
        return redirect()->route('usuario.home')->with('success', 'Item atualizado com sucesso!');
    }

    public function excluirItem($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return redirect()->back()->with('error', 'Item não encontrado.');
        }
        $item->delete();
        return redirect()->route('usuario.home')->with('success', 'Item excluído com sucesso.');
    }

    public function destroy(string $id)
    {
        //
    }
}