<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Localizacao;
use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use App\Models\ItemFoto;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $itens = $user->itens()
            ->with(['categoria', 'localizacao', 'fotos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('usuario.home', compact('itens'));
    }

    public function perfilUsuario()
    {
        $user = Auth::user();
        $parceiros = \App\Models\Parceiro::where('ativo', true)->get();
        return view('usuario.perfil-usuario', compact('user', 'parceiros'));
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
        $messages = [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo :min caracteres.',
            
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo utilizado.',
            
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.size' => 'O telefone deve conter exatamente :size caracteres incluindo formatação.',
            
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.string' => 'A senha deve ser um texto.',
            'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
            
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cpf.size' => 'O CPF deve conter exatamente :size caracteres incluindo pontos e traço.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'telefone' => 'required|string|size:14',
            'senha' => 'required|string|min:5',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cpf' => 'required|string|unique:users,cpf|size:14',
        ], $messages);

        $validatedData['role'] = 'usuario';
        $validatedData['ativo'] = true;

        // Processa o upload da foto se existir
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('avatars', 'public');
            $validatedData['foto'] = $path;
            $validatedData['avatar'] = $path;
        }

        // O Eloquent já faz o hash da senha automaticamente (graças ao cast 'hashed' no model)
        $usuario = User::create($validatedData);
        
        return redirect()->route('form.login')->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
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
        $item = Item::findOrFail($id);
        
        // Validação da localização
        $validatedLocalizacao = $request->validate([
            'nome_local' => 'required|string|max:255',
            'endereco' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'referencia' => 'required|string|max:1000',
        ]);
    
        // Atualiza a localização
        $item->localizacao->update($validatedLocalizacao);
    
        // Validação do item
        $validatedItem = $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descricao' => 'required|string|max:1000',
            'tipo' => 'required|in:achado,perdido',
            'data_perdido' => $request->tipo === 'perdido' ? 'required|date' : 'nullable|date',
            'data_encontrado' => $request->tipo === 'achado' ? 'required|date' : 'nullable|date',
        ]);
    
        // Atualiza o item
        $item->update($validatedItem);
    
        // Remove fotos marcadas para exclusão
        if ($request->has('fotos_removidas')) {
            ItemFoto::whereIn('id', $request->fotos_removidas)->delete();
        }
    
        // Atualiza foto principal
        if ($request->has('foto_principal')) {
            ItemFoto::where('item_id', $item->id)
                ->update(['is_principal' => false]);
                
            ItemFoto::where('id', $request->foto_principal)
                ->update(['is_principal' => true]);
        }
    
        // Adiciona novas fotos
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $key => $foto) {
                $path = $foto->store('imagens', 'public');
                
                ItemFoto::create([
                    'item_id' => $item->id,
                    'caminho' => $path,
                    'ordem' => $key,
                    'is_principal' => false // Nova foto não é principal por padrão
                ]);
            }
        }
    
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

    public function editProfile()
    {
        $user = Auth::user();
        return view('usuario.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.min' => 'O nome deve ter no mínimo :min caracteres.',
            
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo utilizado.',
            
            'telefone.required' => 'O campo telefone é obrigatório.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.size' => 'O telefone deve conter exatamente :size caracteres incluindo formatação.',
            
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            
            'foto.image' => 'O arquivo deve ser uma imagem.',
            'foto.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou gif.',
            'foto.max' => 'A imagem deve ter no máximo :max kilobytes.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefone' => 'required|string|size:14',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:5|confirmed',
        ], $messages);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->telefone = $validatedData['telefone'];

        if ($request->hasFile('foto')) {
            // Remove a foto antiga se existir
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Salva a nova foto
            $path = $request->file('foto')->store('avatars', 'public');
            $user->foto = $path;
            $user->avatar = $path;
        }

        if (!empty($validatedData['password'])) {
            $user->senha = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('perfil-usuario')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        //
    }
    
    public function desativarConta()
    {
        $user = Auth::user();
        $user->ativo = false;
        $user->save();
        
        return redirect()->route('perfil-usuario')->with('warning', 'Sua conta foi desativada e será excluída em 30 dias. Você pode cancelar a exclusão a qualquer momento.');
    }
    
    public function reativarConta()
    {
        $user = Auth::user();
        $user->ativo = true;
        $user->save();
        
        return redirect()->route('perfil-usuario')->with('success', 'Sua conta foi reativada com sucesso!');
    }
}