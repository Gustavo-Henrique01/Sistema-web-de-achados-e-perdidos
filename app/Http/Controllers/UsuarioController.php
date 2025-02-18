<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Endereco;

class UsuarioController extends Controller
{
    
    public function home(){
        return view('usuario.home');
    }

    public function index()
    {
        return view('forms.form-registro');
    }

    public function showLogin (){
        return view('Auth.login');
    }
    
    public function criarUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',            
            'email' => 'required|email|unique:usuarios,email', 
            'telefone' => 'required|string|max:15',       
            'senha' => 'required|string|min:6',            
            'foto' => 'nullable|string',                    
            'cpf' => 'required|string|unique:usuarios,cpf|size:11', 
        ]);
        
        $validatedData['role'] = 'usuario';
        $validatedData['ativo']=true;

        $usuario = Usuario::create($validatedData);
        
        return redirect()->route('form.login');
        
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
    /**
     * Update the specified resource in storage.
     */
    public function editarItem($id)
    {
        // Busca o item pelo ID
        $item = Item::find($id);
    
        // Verifica se o item existe
        if (!$item) {
            return redirect()->route('home')->with('error', 'Item não encontrado.');
        }
    
        // Verifica se o item pertence ao usuário autenticado (opcional, mas recomendado)
        if ($item->id_usuario !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para editar este item.');
        }
    
        // Busca o endereço associado ao item
        $endereco = $item->endereco;
    
        // Retorna a view com os dados do item e do endereço
        return view('forms.form-registroitem', compact('item', 'endereco'));
    }

  public function atualizarItem(Request $request, $id)
  {
      // Busca o item pelo ID
      $item = Item::find($id);
      if (!$item) {
          return redirect()->back()->with('error', 'Item não encontrado.');
      }
  
      // Validação do endereço
      $validatedEndereco = $request->validate([
          'rua' => 'required|string|max:100',
          'numero' => 'nullable|string|max:10',
          'bairro' => 'required|string|max:255',
          'referencial' => 'nullable|string|max:1000',
      ]);

      $validatedEndereco['cidade'] = 'Campo Grande';
      $validatedEndereco['estado'] = 'Mato Grosso do Sul';
  
      // Atualiza o endereço associado ao item
      $endereco = Endereco::find($item->id_endereco);
      if ($endereco) {
          $endereco->update($validatedEndereco);
      } else {
          return redirect()->back()->with('error', 'Endereço não encontrado.');
      }
  
      // Validação do item
      $validatedItem = $request->validate([
          'categoria' => 'required|string|max:255',
          'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Foto é opcional
          'descricao' => 'required|string|max:1000',
          'tipo' => 'required|in:achado,perdido',
      ]);
  
      // Atualiza a foto, se fornecida
      if ($request->hasFile('foto')) {
          // Remove a foto antiga, se existir
          if ($item->foto && Storage::disk('public')->exists($item->foto)) {
              Storage::disk('public')->delete($item->foto);
          }
          // Armazena a nova foto
          $validatedItem['foto'] = $request->file('foto')->store('imagens', 'public');
      }
  
      // Atualiza os dados do item
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
        return redirect()->route('usuario.home')->with('success', 'Item excluído com sucesso
        ');
     }
    public function destroy(string $id)
    {
        //
    }
}
