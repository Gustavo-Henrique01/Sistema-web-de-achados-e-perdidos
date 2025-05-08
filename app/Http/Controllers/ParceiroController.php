<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\User;
use App\Models\Localizacao;
use App\Models\UserRole;
use App\Models\Item;
use App\Models\ItemFoto;
use App\Models\ItemTransferencia;
use App\Models\Categoria;
use App\Notifications\ItemRecebidoNotification;
use App\Notifications\ItemRejeitadoNotification;
use App\Notifications\ItemDevolvidoNotification;
use App\Notifications\ItemConfirmadoNotification;
use Illuminate\Support\Carbon;
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
     * Exibe o formulário para cadastro de item pelo parceiro.
     */
    public function cadastrarItemForm()
    {
        $parceiro = Auth::user()->parceiro;
        
        // Verificar se o parceiro está ativo
        if (!Auth::user()->ativo) {
            return redirect()->route('parceiro.home')
                ->with('error', 'Sua conta está inativa. Entre em contato com a administração.');
        }
        
        // Buscar categorias para o formulário
        $categorias = Categoria::orderBy('nome_categoria')->get();
        
        return view('parceiro.cadastrar-item', compact('parceiro', 'categorias'));
    }
    
    /**
     * Processa o cadastro de item pelo parceiro.
     */
    public function cadastrarItem(Request $request)
    {
        // Verificar se o parceiro está ativo
        $parceiro = Auth::user()->parceiro;
        if (!$parceiro->ativo) {
            return redirect()->route('parceiro.inativo');
        }
        
        // Validar os dados do formulário
        $validator = Validator::make($request->all(), [
            'id_categoria' => 'required|exists:categorias,id',
            'data_encontrado' => 'required|date|before_or_equal:today',
            'descricao' => 'required|string|min:10|max:500',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'endereco' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'nome_local' => 'required',
            'referencia' => 'required',
        ], [
            'id_categoria.required' => 'Por favor, selecione uma categoria para o item.',
            'id_categoria.exists' => 'A categoria selecionada não é válida.',
            'data_encontrado.required' => 'Por favor, informe a data em que o item foi encontrado.',
            'data_encontrado.date' => 'A data informada não é válida.',
            'data_encontrado.before_or_equal' => 'A data não pode ser futura.',
            'descricao.required' => 'Por favor, forneça uma descrição para o item.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode exceder 500 caracteres.',
            'fotos.*.image' => 'O arquivo deve ser uma imagem.',
            'fotos.*.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou webp.',
            'fotos.*.max' => 'A imagem não pode ter mais de 2MB.',
            'endereco.required' => 'Por favor, informe o endereço onde o item foi encontrado.',
            'latitude.required' => 'Por favor, selecione uma localização válida no mapa.',
            'longitude.required' => 'Por favor, selecione uma localização válida no mapa.',
            'nome_local.required' => 'Por favor, informe o nome do local onde o item foi encontrado.',
            'referencia.required' => 'Por favor, informe um ponto de referência para o local onde o item foi encontrado.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Criar uma nova localização para onde o item foi encontrado
            $localizacao = Localizacao::create([
                'endereco' => $request->endereco,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'nome_local' => $request->nome_local,
                'referencia' => $request->referencia
            ]);
            
            // Criar o item usando o método create
            $item = Item::create([
                'id_categoria' => $request->id_categoria,
                'tipo' => 'achado', // Parceiros só podem cadastrar itens achados
                'data_encontrado' => $request->data_encontrado,
                'descricao' => $request->descricao,
                'status' => 'em_estabelecimento', // Status padrão para itens cadastrados por parceiros
                'parceiro_id' => $parceiro->id,
                'user_id' => Auth::id(),
                'id_localizacao' => $localizacao->id
            ]);
            
            // Processar as fotos, se houver
            if ($request->hasFile('fotos')) {
                $fotos = $request->file('fotos');
                $fotoPrincipal = $request->input('foto_principal_index', 0);
                
                // Verificar se não excede o limite de 3 fotos
                if (count($fotos) > 3) {
                    return redirect()->back()
                        ->with('error', 'Você pode enviar no máximo 3 fotos.')
                        ->withInput();
                }
                
                // Processar cada foto
                foreach ($fotos as $index => $foto) {
                    // Verificar se o arquivo é válido
                    if ($foto->isValid()) {
                        $path = $foto->store('itens', 'public');
                        
                        // Determinar se é a foto principal
                        $isPrincipal = false;
                        if (is_numeric($fotoPrincipal) && $index == $fotoPrincipal) {
                            $isPrincipal = true;
                        } elseif ($index == 0 && !is_numeric($fotoPrincipal)) {
                            // Se não houver índice de foto principal, a primeira é a principal
                            $isPrincipal = true;
                        }
                        
                        ItemFoto::create([
                            'item_id' => $item->id,
                            'caminho' => $path,
                            'is_principal' => $isPrincipal
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('parceiro.itens')
                ->with('success', 'Item cadastrado com sucesso! O item foi vinculado ao seu estabelecimento.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao cadastrar o item: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Exibe o formulário para edição do perfil do parceiro.
     */
    public function editProfile()
    {
        $user = Auth::user();
        $parceiro = $user->parceiro;
        
        if (!$parceiro) {
            abort(404, 'Perfil de parceiro não encontrado');
        }
        
        return view('parceiro.edit-profile', compact('user', 'parceiro'));
    }
    
    /**
     * Atualiza o perfil do parceiro.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $parceiro = $user->parceiro;
        
        if (!$parceiro) {
            abort(404, 'Perfil de parceiro não encontrado');
        }
        
        // Validar os dados do formulário
        $validator = Validator::make($request->all(), [
            'nome_estabelecimento' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'horario_funcionamento' => 'nullable|string|max:255',
            'telefone_comercial' => 'required|string|max:15',
            'tipo_parceiro' => 'required|in:ponto_coleta,evento,ambos',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'password' => 'nullable|min:5|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Atualizar dados do parceiro
        $parceiro->nome_estabelecimento = $request->nome_estabelecimento;
        $parceiro->descricao = $request->descricao;
        $parceiro->horario_funcionamento = $request->horario_funcionamento;
        $parceiro->telefone_comercial = $request->telefone_comercial;
        $parceiro->tipo_parceiro = $request->tipo_parceiro;
        
        // Processar o upload da logo, se fornecida
        if ($request->hasFile('logo')) {
            // Excluir a logo antiga, se existir
            if ($parceiro->logo && Storage::exists('public/' . $parceiro->logo)) {
                Storage::delete('public/' . $parceiro->logo);
            }
            
            // Salvar a nova logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $parceiro->logo = $logoPath;
        }
        
        $parceiro->save();
        
        // Atualizar dados do usuário
        $user->email = $request->email;
        
        // Atualizar senha, se fornecida
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('parceiro.home')
            ->with('success', 'Perfil atualizado com sucesso!');
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
     * Exibe formulário para edição de parceiro.
     */
    public function editarCadastro(Parceiro $parceiro)
    {
        // Verificar se o usuário atual é o dono do parceiro ou um administrador
        if (Auth::id() !== $parceiro->user_id  ) {
            abort(403, 'Você não tem permissão para editar este parceiro.');
        }
        
        // Buscar dados do usuário associado ao parceiro
        $usuario = $parceiro->usuario;
        
        // Buscar dados da localização associada ao parceiro
        $localizacao = $parceiro->localizacao;
        
        return view('forms.form-parceiro', [
            'parceiro' => $parceiro,
            'usuario' => $usuario,
            'localizacao' => $localizacao,
            'isEdit' => true
        ]);
    }

    /**
     * Armazena um novo parceiro no banco de dados ou atualiza um existente.
     */
    public function store(Request $request)
    {
        // Verificar se é uma edição ou criação de parceiro
        $isEdit = $request->has('parceiro_id');
        $parceiroId = $request->parceiro_id;
        $parceiro = null; // Inicializar a variável $parceiro para evitar erro de variável indefinida
        
        // Regras de validação diferentes para edição e criação
        $validationRules = [
            'nome_estabelecimento' => 'required|string|max:255',
            'tipo_parceiro' => 'required|in:ponto_coleta,evento,ambos',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
        ];
        
        // Adicionar regras específicas para criação ou edição
        if ($isEdit) {
            $parceiro = Parceiro::findOrFail($parceiroId);
            $usuario = $parceiro->usuario;
            
            // Regras para edição - verificar unicidade exceto para o registro atual
            $validationRules['cnpj'] = 'required|string|max:18|unique:parceiros,cnpj,'.$parceiro->id;
            $validationRules['email'] = 'required|email|unique:users,email,'.$usuario->id;
            $validationRules['cpf'] = 'required|string|unique:users,cpf,'.$usuario->id.'|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';
            
            // Senha opcional na edição
            if ($request->filled('senha')) {
                $validationRules['senha'] = 'string|min:6|confirmed';
            }
        } else {
            // Regras para criação
            $validationRules['cnpj'] = 'required|string|max:18|unique:parceiros,cnpj';
            $validationRules['email'] = 'required|email|unique:users,email';
            $validationRules['cpf'] = 'required|string|unique:users,cpf|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';
            $validationRules['senha'] = 'required|string|min:6|confirmed';
        }
        
        $validator = Validator::make($request->all(), $validationRules, [
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
            'cnpj.max' => 'O CNPJ deve ter no máximo 18 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado no sistema.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00',
            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'A imagem deve ser do tipo: jpeg, jpg, png ou gif.',
            'logo.max' => 'A imagem não pode ser maior que 2MB.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Log dos dados recebidos
            \Log::info('Dados recebidos no cadastro/edição de parceiro:', $request->all());
            \Log::info('Tipo de operação: ' . ($isEdit ? 'Edição' : 'Criação'));

            // Validação dos dados do usuário - ajustada para edição/criação
            $userValidationRules = [
                'name' => 'required|string|max:255',
                'telefone' => 'required|string|max:15',
            ];
            
            // Adicionar regras específicas para email e CPF
            if ($isEdit) {
                $parceiro = Parceiro::findOrFail($parceiroId);
                $usuario = $parceiro->usuario;
                $userValidationRules['email'] = 'required|email|unique:users,email,'.$usuario->id;
                $userValidationRules['cpf'] = 'required|string|unique:users,cpf,'.$usuario->id.'|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';
                
                // Senha opcional na edição
                if ($request->filled('senha')) {
                    $userValidationRules['senha'] = 'string|min:6|confirmed';
                }
            } else {
                $userValidationRules['email'] = 'required|email|unique:users,email';
                $userValidationRules['cpf'] = 'required|string|unique:users,cpf|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/';
                $userValidationRules['senha'] = 'required|string|min:6|confirmed';
            }
            
            $validatedUserData = $request->validate($userValidationRules);

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
                if ($isEdit) {
                    // Atualizar usuário existente
                    $parceiro = Parceiro::findOrFail($parceiroId);
                    $user = $parceiro->usuario;
                    
                    \Log::info('Atualizando usuário com os dados:', $validatedUserData);
                    
                    $user->name = $validatedUserData['name'];
                    $user->email = $validatedUserData['email'];
                    $user->telefone = $validatedUserData['telefone'];
                    $user->cpf = $validatedUserData['cpf'];
                    
                    // Atualizar senha apenas se fornecida
                    if ($request->filled('senha')) {
                        $user->senha = Hash::make($request->senha);
                    }
                    
                    $user->save();
                    
                    // Se o parceiro estava reprovado, mudar para pendente novamente
                    if ($parceiro->status === 'reprovado') {
                        $parceiro->status = 'pendente';
                        $parceiro->motivo_reprovacao = null;
                    }
                } else {
                    // Criar usuário com papel de parceiro
                    \Log::info('Iniciando criação do usuário com os dados:', $validatedUserData);
                    
                    try {
                        $user = User::create([
                            'name' => $validatedUserData['name'],
                            'email' => $validatedUserData['email'],
                            'telefone' => $validatedUserData['telefone'],
                            'senha' => Hash::make($validatedUserData['senha']), // Hash explícito da senha
                            'cpf' => $validatedUserData['cpf'],
                            'role' => UserRole::PARCEIRO->value,
                            'ativo' => false, 
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Erro ao criar usuário: ' . $e->getMessage());
                        throw new \Exception('Erro ao criar usuário: ' . $e->getMessage());
                    }
                }

                \Log::info('Usuário criado com sucesso:', ['user_id' => $user->id]);

                \Log::info('Iniciando criação da localização com os dados:', $validatedLocalizacaoData);
            
                if ($isEdit) {
                    // Atualizar localização existente
                    $localizacao = $parceiro->localizacao;
                    $localizacao->nome_local = $validatedLocalizacaoData['nome_local'];
                    $localizacao->endereco = $validatedLocalizacaoData['endereco'];
                    $localizacao->latitude = $validatedLocalizacaoData['latitude'];
                    $localizacao->longitude = $validatedLocalizacaoData['longitude'];
                    $localizacao->referencia = $validatedLocalizacaoData['referencia'] ?? null;
                    $localizacao->save();
                } else {
                    // Criar localização para o parceiro
                    try {
                        $localizacao = Localizacao::create([
                            'nome_local' => $validatedLocalizacaoData['nome_local'],
                            'endereco' => $validatedLocalizacaoData['endereco'],
                            'latitude' => $validatedLocalizacaoData['latitude'],
                            'longitude' => $validatedLocalizacaoData['longitude'],
                            'referencia' => $validatedLocalizacaoData['referencia'] ?? null,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Erro ao criar localização: ' . $e->getMessage());
                        throw new \Exception('Erro ao criar localização: ' . $e->getMessage());
                    }
                }

                \Log::info('Localização criada com sucesso:', ['localizacao_id' => $localizacao->id]);

                \Log::info('Iniciando criação do parceiro com os dados:', $validatedParceiroData);
            
                if ($isEdit) {
                    // Atualizar parceiro existente
                    $parceiro->nome_estabelecimento = $validatedParceiroData['nome_estabelecimento'];
                    $parceiro->cnpj = $request->cnpj;
                    $parceiro->descricao = $validatedParceiroData['descricao'] ?? null;
                    $parceiro->horario_funcionamento = $validatedParceiroData['horario_funcionamento'] ?? null;
                    $parceiro->telefone_comercial = $validatedParceiroData['telefone_comercial'] ?? null;
                    $parceiro->tipo_parceiro = $validatedParceiroData['tipo_parceiro'];
                    
                    // Atualizar logo apenas se fornecida
                    if ($request->hasFile('logo')) {
                        // Remover logo antiga se existir
                        if ($parceiro->logo) {
                            Storage::disk('public')->delete($parceiro->logo);
                        }
                        $parceiro->logo = $validatedParceiroData['logo'];
                    }
                    
                    $parceiro->save();
                } else {
                    // Criar parceiro
                    try {
                        \Log::info('Tentando criar parceiro com os dados:', [
                            'nome_estabelecimento' => $validatedParceiroData['nome_estabelecimento'],
                            'cnpj' => $request->cnpj,
                            'user_id' => $user->id,
                            'id_localizacao' => $localizacao->id,
                            'data_inicio_parceria' => now()->toDateString()
                        ]);
                        
                        $parceiro = Parceiro::create([
                            'nome_estabelecimento' => $validatedParceiroData['nome_estabelecimento'],
                            'cnpj' => $request->cnpj,
                            'descricao' => $validatedParceiroData['descricao'] ?? null,
                            'horario_funcionamento' => $validatedParceiroData['horario_funcionamento'] ?? null,
                            'telefone_comercial' => $validatedParceiroData['telefone_comercial'] ?? null,
                            'tipo_parceiro' => $validatedParceiroData['tipo_parceiro'],
                            'status' => 'pendente',
                            'logo' => $validatedParceiroData['logo'] ?? null,
                            'user_id' => $user->id,
                            'id_localizacao' => $localizacao->id,
                            'data_inicio_parceria' => now()->toDateString(),
                            'ativo' => true,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Erro ao criar parceiro: ' . $e->getMessage());
                        throw new \Exception('Erro ao criar parceiro: ' . $e->getMessage());
                    }
                }

                \Log::info('Parceiro criado com sucesso:', ['parceiro_id' => $parceiro->id]);

                DB::commit();
                \Log::info('Transação concluída com sucesso');

                // Mensagem e redirecionamento diferentes para criação e edição
                if ($isEdit) {
                    return redirect()->route('parceiro.aguardando-aprovacao')
                        ->with('success', 'Cadastro atualizado com sucesso! Aguarde a nova análise do administrador.');
                } else {
                    // Redirecionar para a página de aguardando aprovação
                    return redirect()->route('parceiro.aguardando-aprovacao')
                        ->with('success', 'Cadastro realizado com sucesso! Aguarde a aprovação do administrador.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Erro no bloco try interno: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // Remover imagem se houve upload
                if (isset($validatedParceiroData['logo']) && $parceiro !== null && $validatedParceiroData['logo'] != $parceiro->getOriginal('logo')) {
                    Storage::disk('public')->delete($validatedParceiroData['logo']);
                } elseif (isset($validatedParceiroData['logo'])) {
                    // Se $parceiro não existe, apenas remova a imagem
                    Storage::disk('public')->delete($validatedParceiroData['logo']);
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Erro ao processar cadastro: ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro no bloco try externo: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Remover imagem se houve upload
            if (isset($validatedParceiroData['logo']) && $parceiro !== null && $validatedParceiroData['logo'] != $parceiro->getOriginal('logo')) {
                Storage::disk('public')->delete($validatedParceiroData['logo']);
            } elseif (isset($validatedParceiroData['logo'])) {
                // Se $parceiro não existe, apenas remova a imagem
                Storage::disk('public')->delete($validatedParceiroData['logo']);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao processar cadastro: ' . $e->getMessage()]);
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
     * Exibe detalhes públicos de um parceiro específico
     */
    public function detalhesPublicos(Parceiro $parceiro)
    {
        // Verificar se o parceiro está ativo e aprovado
        if (!$parceiro->ativo || $parceiro->status !== 'aprovado') {
            abort(404, 'Parceiro não encontrado');
        }
        
        // Carregar relacionamentos necessários
        $parceiro->load(['localizacao', 'itens' => function($query) {
            $query->where('status', 'em_estabelecimento')
                  ->with(['categoria', 'fotos'])
                  ->orderBy('updated_at', 'desc')
                  ->limit(5);
        }]);
        
        // Contar itens por status
        $estatisticas = [
            'total_itens' => $parceiro->itens()->where('status', 'em_estabelecimento')->count(),
            'itens_devolvidos' => $parceiro->itens()->where('status', 'devolvido')->count(),
        ];
        
        return view('parceiro.detalhes-publicos', compact('parceiro', 'estatisticas'));
    }
    
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
   
    /**
     * Exibe a mensagem de parceiro inativo.
     */
   

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

    /**
     * Exibe a mensagem de parceiro inativo.
     */
    public function inativo()
    {
        $parceiro = auth()->user()->parceiro;
        
        if (!$parceiro) {
            return redirect()->route('parceiro.cadastro');
        }
        
        if ($parceiro->ativo) {
            return redirect()->route('parceiro.home');
        }
        
        return view('parceiro.mensagem-parceiro-inativo');
    }

    /**
     * Exibe formulário para edição de item cadastrado pelo parceiro.
     */
    public function editarItem(Item $item)
    {
        // Verificar se o item pertence ao parceiro
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Este item não pertence ao seu estabelecimento.']);
        }
        
        // Verificar se o item pode ser editado (apenas itens em estabelecimento)
        if ($item->status !== Item::STATUS_EM_ESTABELECIMENTO) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Apenas itens em estabelecimento podem ser editados.']);
        }
        
        // Buscar categorias para o formulário
        $categorias = Categoria::orderBy('nome_categoria')->get();
        
        // Definir flag de edição
        $isEdit = true;
        
        // Usar a mesma view de cadastro, mas com flag de edição
        return view('parceiro.cadastrar-item', compact('item', 'categorias', 'isEdit'));
    }
    
    /**
     * Atualiza um item cadastrado pelo parceiro.
     */
    public function atualizarItem(Request $request, Item $item)
    {
        // Verificar se o item pertence ao parceiro
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Este item não pertence ao seu estabelecimento.']);
        }
        
        // Verificar se o item pode ser editado (apenas itens em estabelecimento)
        if ($item->status !== Item::STATUS_EM_ESTABELECIMENTO) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Apenas itens em estabelecimento podem ser editados.']);
        }
        
        // Validar os dados do formulário
        $validator = Validator::make($request->all(), [
            'id_categoria' => 'required|exists:categorias,id',
            'data_encontrado' => 'required|date|before_or_equal:today',
            'descricao' => 'required|string|min:10|max:500',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'endereco' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'nome_local' => 'required',
            'referencia' => 'required',
        ], [
            'id_categoria.required' => 'Por favor, selecione uma categoria para o item.',
            'id_categoria.exists' => 'A categoria selecionada não é válida.',
            'data_encontrado.required' => 'Por favor, informe a data em que o item foi encontrado.',
            'data_encontrado.date' => 'A data informada não é válida.',
            'data_encontrado.before_or_equal' => 'A data não pode ser futura.',
            'descricao.required' => 'Por favor, forneça uma descrição para o item.',
            'descricao.min' => 'A descrição deve ter pelo menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode exceder 500 caracteres.',
            'fotos.*.image' => 'O arquivo deve ser uma imagem.',
            'fotos.*.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg ou webp.',
            'fotos.*.max' => 'A imagem não pode ter mais de 2MB.',
            'endereco.required' => 'Por favor, informe o endereço onde o item foi encontrado.',
            'latitude.required' => 'Por favor, selecione uma localização válida no mapa.',
            'longitude.required' => 'Por favor, selecione uma localização válida no mapa.',
            'nome_local.required' => 'Por favor, informe o nome do local onde o item foi encontrado.',
            'referencia.required' => 'Por favor, informe um ponto de referência para o local onde o item foi encontrado.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Atualizar dados do item
            $item->update([
                'id_categoria' => $request->id_categoria,
                'data_encontrado' => $request->data_encontrado,
                'descricao' => $request->descricao,
                // Mantém o tipo e status originais
            ]);
            
            // Atualizar localização
            if ($item->localizacao) {
                $item->localizacao->update([
                    'endereco' => $request->endereco,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'nome_local' => $request->nome_local,
                    'referencia' => $request->referencia,
                ]);
            } else {
                Localizacao::create([
                    'item_id' => $item->id,
                    'endereco' => $request->endereco,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'nome_local' => $request->nome_local,
                    'referencia' => $request->referencia,
                ]);
            }
            
            // Processar as fotos, se houver
            if ($request->hasFile('fotos')) {
                $fotos = $request->file('fotos');
                $fotoPrincipal = $request->input('foto_principal_index', 0);
                
                // Verificar se não excede o limite de 3 fotos (considerando as já existentes)
                $totalFotos = $item->fotos->count() + count($fotos);
                if ($totalFotos > 3) {
                    return redirect()->back()
                        ->with('error', 'Você pode ter no máximo 3 fotos por item.')
                        ->withInput();
                }
                
                // Processar cada foto
                foreach ($fotos as $index => $foto) {
                    // Verificar se o arquivo é válido
                    if ($foto->isValid()) {
                        $path = $foto->store('itens', 'public');
                        
                        // Determinar se é a foto principal
                        $isPrincipal = false;
                        if (is_numeric($fotoPrincipal) && $index == $fotoPrincipal) {
                            // Se esta foto for marcada como principal, atualizar todas as outras para não-principal
                            if ($item->fotos->count() > 0) {
                                foreach ($item->fotos as $existingFoto) {
                                    $existingFoto->update(['is_principal' => false]);
                                }
                            }
                            $isPrincipal = true;
                        }
                        
                        ItemFoto::create([
                            'item_id' => $item->id,
                            'caminho' => $path,
                            'is_principal' => $isPrincipal
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('parceiro.itens')
                ->with('success', 'Item atualizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao atualizar o item: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Exclui um item cadastrado pelo parceiro.
     */
    public function excluirItem(Item $item)
    {
        // Verificar se o item pertence ao parceiro
        if ($item->parceiro_id !== auth()->user()->parceiro->id) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Este item não pertence ao seu estabelecimento.']);
        }
        
        // Verificar se o item pode ser excluído (apenas itens em estabelecimento)
        if ($item->status !== Item::STATUS_EM_ESTABELECIMENTO) {
            return redirect()->route('parceiro.itens')
                ->withErrors(['error' => 'Apenas itens em estabelecimento podem ser excluídos.']);
        }
        
        try {
            DB::beginTransaction();
            
            // Excluir as fotos do item
            foreach ($item->fotos as $foto) {
                if (Storage::disk('public')->exists($foto->caminho)) {
                    Storage::disk('public')->delete($foto->caminho);
                }
                $foto->delete();
            }
            
            // Excluir o item
            $item->delete();
            
            DB::commit();
            
            return redirect()->route('parceiro.itens')
                ->with('success', 'Item excluído com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Registrar erro no log
            \Log::error('Erro ao excluir item por parceiro', [
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'parceiro_id' => Auth::user()->parceiro->id ?? 'N/A',
                'item_id' => $item->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Ocorreu um erro ao excluir o item: ' . $e->getMessage());
        }
    }
}