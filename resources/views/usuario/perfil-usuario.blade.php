@extends('usuario.home')

@section('content')
@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Adicionar links para o Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<style>
    /* Estilos para o autocomplete */
    .ui-autocomplete {
        position: absolute;
        z-index: 9999 !important;
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        padding: 5px 0;
    }
    
    .ui-menu-item {
        padding: 5px 10px;
        cursor: pointer;
        list-style: none;
    }
    
    .ui-menu-item:hover,
    .ui-state-active {
        background-color: #f0f0f0;
    }
    
    .ui-helper-hidden-accessible {
        display: none;
    }
    
    /* Estilos para o perfil do usuário */
    .profile-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-bottom: 30px;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 20px;
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        top: 60px;
        margin-bottom: 30px;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-info {
        padding: 80px 20px 20px;
        background-color: white;
    }
    
    .profile-stats {
        background-color: #f8f9fc;
        padding: 15px;
        border-top: 1px solid #e3e6f0;
        font-size: 0.875rem;
    }
    
    .stat-item {
        padding: 8px 0;
        border-bottom: 1px dashed #e3e6f0;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    /* Estilos para os itens cadastrados */
    .items-container {
        margin-bottom: 30px;
    }
    
    .items-header {
        background: linear-gradient(135deg, #36b9cc 0%, #1a8a98 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
    }
    
    .items-body {
        background-color: white;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 20px;
    }
    
    .item-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        height: 100%;
    }
    
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2);
    }
    
    .item-gallery {
        position: relative;
    }
    
    .main-photo {
        height: 200px;
        background-color: #e9ecef;
        overflow: hidden;
        position: relative;
    }
    
    .main-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-thumbnails {
        display: flex;
        padding: 5px;
        background-color: rgba(0,0,0,0.03);
        overflow-x: auto;
    }
    
    .photo-thumbnail {
        width: 50px;
        height: 50px;
        margin-right: 5px;
        border: 2px solid #fff;
        border-radius: 4px;
        cursor: pointer;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .photo-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    
    .photo-thumbnail:hover img {
        transform: scale(1.1);
    }
    
    .photo-thumbnail.active {
        border-color: #4e73df;
    }
    
    /* Ícones de navegação da galeria */
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.7);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        color: #333;
        transition: all 0.2s;
    }
    
    .gallery-nav:hover {
        background: rgba(255,255,255,0.9);
    }
    
    .gallery-prev {
        left: 10px;
    }
    
    .gallery-next {
        right: 10px;
    }
    
    .item-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .badge-achado {
        background-color: #1cc88a;
        color: white;
    }
    
    .badge-perdido {
        background-color: #e74a3b;
        color: white;
    }
    
    .item-content {
        padding: 15px;
    }
    
    .item-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #4e73df;
    }
    
    .item-info {
        font-size: 0.875rem;
        color: #5a5c69;
        margin-bottom: 5px;
    }
    
    .item-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
    }
    
    .pagination-container {
        margin-top: 20px;
    }
    
    .no-items {
        padding: 30px;
        text-align: center;
        color: #6c757d;
    }
    
    .no-items i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #ddd;
    }
    
    /* Estilos para o filtro de status */
    .status-filter {
        margin-bottom: 20px;
    }
    
    .filter-btn {
        border-radius: 20px;
        font-size: 0.85rem;
        margin-right: 5px;
        padding: 5px 15px;
        opacity: 0.7;
        transition: all 0.2s;
    }
    
    .filter-btn:hover, .filter-btn.active {
        opacity: 1;
        transform: translateY(-2px);
    }
    
    .filter-btn.all {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .filter-btn.approved {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    
    .filter-btn.pending {
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    
    .filter-btn.rejected {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
            top: 50px;
        }
        
        .profile-info {
            padding-top: 70px;
        }
        
        .main-photo {
            height: 150px;
        }
    }
</style>

    <div class="container mt-5">
        <div class="row">
        <!-- Perfil do Usuário -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Perfil do Usuário</h4>
                    <p class="text-white-50">Membro desde {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
                    </div>
                
                <!-- Avatar do Usuário -->
                <div class="profile-avatar">
                        @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto do Usuário">
                        @else
                        <span class="text-muted">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <!-- Informações do Usuário -->
                <div class="profile-info">
                    <h4 class="text-center mb-4">{{ $user->name }}</h4>
                    
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Email:</strong></div>
                                <div class="col-7 text-end">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Telefone:</strong></div>
                                <div class="col-7 text-end">{{ $user->telefone ?: 'Não informado' }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>CPF:</strong></div>
                                <div class="col-7 text-end">{{ $user->cpf ?: 'Não informado' }}</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Status:</strong></div>
                                <div class="col-7 text-end">
                                    <span class="badge {{ $user->ativo ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="row">
                                <div class="col-5"><strong>Função:</strong></div>
                                <div class="col-7 text-end">{{ $user->role->value }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botões de Ação -->
                <div class="profile-stats">
                    <div class="d-grid gap-2">
                        <a href="{{ route('usuario.edit-profile') }}" class="btn btn-primary">Editar Perfil</a>
                        <a href="{{ url('/chatify') }}" class="btn btn-success">
                            <i class="fas fa-comments me-2"></i>Mensagens
                        </a>
                        
                        @if($user->ativo)
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-user-times me-2"></i>Excluir Conta
                            </button>
                        @else
                            <form action="{{ route('usuario.reativar-conta') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-user-check me-2"></i>Cancelar Exclusão da Conta
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estatísticas do Usuário -->
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Estatísticas</h4>
                </div>
                
                <div class="profile-info p-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4>{{ $user->itens->count() }}</h4>
                            <p class="text-muted mb-0">Itens cadastrados</p>
                        </div>
                        <div class="col-6">
                            <h4>{{ $user->itens->where('status', 'aprovado')->count() }}</h4>
                            <p class="text-muted mb-0">Itens aprovados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagens Recentes -->
            <div class="profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">Mensagens Recentes</h4>
                </div>
                
                <div class="list-group list-group-flush">
                    <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-inbox me-2 text-primary"></i>
                            <span>Ver todas as mensagens</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Itens Cadastrados -->
        <div class="col-lg-8">
            <div class="items-container">
                <div class="items-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Meus Itens Cadastrados</h4>
                    <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-light btn-sm">Cadastrar Novo Item</a>
                </div>
                
                <div class="items-body">
                    <!-- Filtro de status -->
                    <div class="status-filter">
                        <div class="d-flex flex-wrap justify-content-center">
                            <button type="button" class="btn btn-sm filter-btn all active" data-status="todos">
                                <i class="fas fa-list"></i> Todos <span class="badge bg-white text-primary">{{ $user->itens->count() }}</span>
                            </button>
                            <button type="button" class="btn btn-sm filter-btn approved" data-status="aprovado">
                                <i class="fas fa-check-circle"></i> Aprovados <span class="badge bg-white text-success">{{ $user->itens->where('status', 'aprovado')->count() }}</span>
                            </button>
                            <button type="button" class="btn btn-sm filter-btn pending" data-status="pendente">
                                <i class="fas fa-clock"></i> Pendentes <span class="badge bg-white text-warning">{{ $user->itens->where('status', 'pendente')->count() }}</span>
                            </button>
                            <button type="button" class="btn btn-sm filter-btn rejected" data-status="reprovado">
                                <i class="fas fa-times-circle"></i> Reprovados <span class="badge bg-white text-danger">{{ $user->itens->where('status', 'reprovado')->count() }}</span>
                            </button>
                        </div>
                    </div>
                    
                        @if ($user->itens->isEmpty())
                        <div class="no-items">
                            <i class="fas fa-box-open d-block"></i>
                            <p>Você ainda não cadastrou nenhum item.</p>
                            <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary">Cadastrar novo item</a>
                        </div>
                        @else
                            <div class="row">
                                @foreach ($user->itens as $item)
                                <div class="col-md-6 mb-4 item-container" data-status="{{ $item->status }}">
                                    <div class="item-card">
                                        <div class="item-gallery">
                                            <!-- Foto principal -->
                                            <div class="main-photo" id="main-photo-{{ $item->id }}">
                                                @if ($item->fotos && $item->fotos->isNotEmpty())
                                                    @php
                                                        $fotoPrincipal = $item->fotos->where('is_principal', true)->first();
                                                        $foto = $fotoPrincipal ?? $item->fotos->first();
                                                    @endphp
                                                    @if($foto && $foto->caminho)
                                                        <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Foto do Item">
                                                        
                                                        <!-- Navegação da galeria (apenas se houver mais de uma foto) -->
                                                        @if($item->fotos->count() > 1)
                                                            <div class="gallery-nav gallery-prev" onclick="prevPhoto({{ $item->id }})">
                                                                <i class="fas fa-chevron-left"></i>
                                                            </div>
                                                            <div class="gallery-nav gallery-next" onclick="nextPhoto({{ $item->id }})">
                                                                <i class="fas fa-chevron-right"></i>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center h-100">
                                                            <i class="fas fa-image text-muted fa-3x"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100">
                                                        <i class="fas fa-image text-muted fa-3x"></i>
                                                    </div>
                                                @endif
                                                
                                                <div class="item-badge badge-{{ $item->tipo }}">
                                                    {{ ucfirst($item->tipo) }}
                                                </div>
                                            </div>
                                            
                                            <!-- Miniaturas das fotos (apenas se houver mais de uma foto) -->
                                            @if ($item->fotos && $item->fotos->count() > 1)
                                                <div class="photo-thumbnails">
                                                    @foreach($item->fotos as $foto)
                                                        @if($foto->caminho)
                                                            <div class="photo-thumbnail {{ $foto->is_principal ? 'active' : '' }}" 
                                                                 onclick="changeMainPhoto({{ $item->id }}, '{{ asset('storage/' . $foto->caminho) }}', this)">
                                                                <img src="{{ asset('storage/' . $foto->caminho) }}" alt="Miniatura">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="item-content">
                                            <div class="item-title">{{ $item->categoria->nome_categoria }}</div>
                                            
                                            <div class="item-info">
                                                <p class="text-truncate"><strong>Descrição:</strong> {{ $item->descricao }}</p>
                                                
                                                @if($item->tipo == 'perdido' && $item->data_perdido)
                                                    <p><strong>Perdido em:</strong> {{ \Carbon\Carbon::parse($item->data_perdido)->format('d/m/Y') }}</p>
                                                @elseif($item->tipo == 'achado' && $item->data_encontrado)
                                                    <p><strong>Encontrado em:</strong> {{ \Carbon\Carbon::parse($item->data_encontrado)->format('d/m/Y') }}</p>
                                                @endif
                                                
                                                <p><strong>Local:</strong> {{ $item->localizacao->nome_local ?? 'Não informado' }}</p>
                                                
                                                <p>
                                                    <strong>Status:</strong> 
                                                    <span class="badge {{ $item->status == 'aprovado' ? 'bg-success' : ($item->status == 'pendente' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="item-footer">
                                            <div class="d-flex flex-wrap gap-1 justify-content-between w-100">
                                                <div>
                                                    <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </a>
                                                </div>
                                                <div>
                                                    @if($item->status === 'aprovado' && !$item->parceiro_id)
                                                    <button type="button" 
                                                            class="btn btn-success btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#enviarParaParceiroModal-{{ $item->id }}">
                                                        <i class="fas fa-store"></i> Enviar para Ponto de Coleta
                                                    </button>
                                                    @endif
                                                    
                                                    @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#marcarDevolvidoModal-{{ $item->id }}">
                                                        <i class="fas fa-handshake"></i> Marcar como Devolvido
                                                    </button>
                                                    @elseif($item->status === 'devolvido')
                                                    <span class="badge bg-success p-2">
                                                        <i class="fas fa-check-circle me-1"></i> Item Devolvido
                                                    </span>
                                                    @endif
                                                    
                                                    <form action="{{ route('usuario.deletar-item', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este item?');">
                                                            <i class="fas fa-trash"></i> Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modais para marcar itens como devolvidos -->
@foreach($user->itens as $item)
    @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
    <!-- Modal de Devolução para o item {{ $item->id }} -->
    <div class="modal fade" id="marcarDevolvidoModal-{{ $item->id }}" tabindex="-1" aria-labelledby="marcarDevolvidoModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="marcarDevolvidoModalLabel-{{ $item->id }}">
                        <i class="fas fa-handshake me-2"></i>Marcar Item como Devolvido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form action="{{ route('item.marcar-como-devolvido', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="mb-3">Como este item foi devolvido?</p>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="tipo_devolucao" id="tipo_usuario-{{ $item->id }}" value="usuario" checked>
                            <label class="form-check-label" for="tipo_usuario-{{ $item->id }}">
                                <i class="fas fa-user me-2 text-primary"></i>Um usuário do sistema me devolveu
                            </label>
                            <div class="mt-2 ps-4 usuario-devolucao-container-{{ $item->id }}">
                                <input type="text" class="form-control usuario-autocomplete" 
                                       id="usuario_devolucao-{{ $item->id }}" 
                                       name="usuario_email" 
                                       placeholder="Digite o email ou nome do usuário" 
                                       required>
                                <input type="hidden" id="usuario_devolucao_id-{{ $item->id }}" name="usuario_devolucao_id">
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="tipo_devolucao" id="tipo_proprio-{{ $item->id }}" value="proprio">
                            <label class="form-check-label" for="tipo_proprio-{{ $item->id }}">
                                <i class="fas fa-search me-2 text-success"></i>Eu mesmo encontrei o item
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="tipo_devolucao" id="tipo_parceiro-{{ $item->id }}" value="parceiro">
                            <label class="form-check-label" for="tipo_parceiro-{{ $item->id }}">
                                <i class="fas fa-store me-2 text-info"></i>Peguei no parceiro
                            </label>
                            <div class="mt-2 ps-4 parceiro-devolucao-container-{{ $item->id }}" style="display: none;">
                                <select class="form-select" id="parceiro_devolucao-{{ $item->id }}" name="parceiro_devolucao_id">
                                    <option value="">Selecione o parceiro</option>
                                    @foreach($parceiros as $parceiro)
                                        <option value="{{ $parceiro->id }}">{{ $parceiro->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observacoes-{{ $item->id }}" class="form-label">Observações (opcional):</label>
                            <textarea class="form-control" id="observacoes-{{ $item->id }}" name="observacoes" rows="3" placeholder="Adicione informações adicionais sobre a devolução"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle me-2"></i>Confirmar Devolução
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- Adicionar Font Awesome para ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Adicionar jQuery UI para autocomplete -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<style>
    /* Estilos adicionais para garantir que o autocomplete seja visível */
    .ui-front {
        z-index: 10000 !important; /* Garante que o dropdown fique acima do modal */
    }
</style>

<!-- Scripts para a galeria de fotos -->
<script>
    // Array para armazenar as fotos de cada item
    const itemGalleries = {};
    
    // Inicializa os arrays de fotos para cada item que tem múltiplas fotos
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($user->itens as $item)
            @if($item->fotos && $item->fotos->count() > 1)
                itemGalleries[{{ $item->id }}] = [
                    @foreach($item->fotos as $foto)
                        "{{ asset('storage/' . $foto->caminho) }}",
                    @endforeach
                ];
            @endif
        @endforeach
        
        // Inicializa os filtros
        initStatusFilter();
        
        // Inicializa o autocomplete para usuários
        initUsuarioAutocomplete();
        
        // Inicializa os controles do modal de devolução
        initDevolucaoControls();
    });
    
    // Inicializa o autocomplete para busca de usuários
    function initUsuarioAutocomplete() {
        $('.usuario-autocomplete').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('usuarios.search') }}",
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + ' (' + item.email + ')',
                                value: item.email,
                                id: item.id
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                // Extrai o ID do item do ID do campo
                const fieldId = $(this).attr('id');
                const itemId = fieldId.split('-')[1];
                
                // Define o ID do usuário no campo oculto
                $('#usuario_devolucao_id-' + itemId).val(ui.item.id);
                return true;
            }
        });
    }
    
    // Inicializa os controles do modal de devolução
    function initDevolucaoControls() {
        // Para cada item, configura os controles do modal
        @foreach($user->itens as $item)
            @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
                // Controla a exibição dos campos com base na opção selecionada
                $('input[name="tipo_devolucao"]').change(function() {
                    const itemId = $(this).attr('id').split('-')[1];
                    const tipoSelecionado = $(this).val();
                    
                    // Esconde todos os containers específicos
                    $('.usuario-devolucao-container-' + itemId).hide();
                    $('.parceiro-devolucao-container-' + itemId).hide();
                    
                    // Remove o atributo required de todos os campos
                    $('#usuario_devolucao-' + itemId).prop('required', false);
                    $('#parceiro_devolucao-' + itemId).prop('required', false);
                    
                    // Mostra o container específico com base na opção selecionada
                    if (tipoSelecionado === 'usuario') {
                        $('.usuario-devolucao-container-' + itemId).show();
                        $('#usuario_devolucao-' + itemId).prop('required', true);
                    } else if (tipoSelecionado === 'parceiro') {
                        $('.parceiro-devolucao-container-' + itemId).show();
                        $('#parceiro_devolucao-' + itemId).prop('required', true);
                    }
                });
                
                // Trigger change event para configurar o estado inicial
                $('#tipo_usuario-{{ $item->id }}').trigger('change');
            @endif
        @endforeach
    }
    
    // Função para filtrar itens por status
    function initStatusFilter() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const itemContainers = document.querySelectorAll('.item-container');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove a classe active de todos os botões
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adiciona a classe active ao botão clicado
                this.classList.add('active');
                
                const selectedStatus = this.getAttribute('data-status');
                
                // Filtra os itens com base no status selecionado
                itemContainers.forEach(container => {
                    if (selectedStatus === 'todos' || container.getAttribute('data-status') === selectedStatus) {
                        container.style.display = '';
                    } else {
                        container.style.display = 'none';
                    }
                });
                
                // Verifica se há itens visíveis para o status atual
                checkNoVisibleItems(selectedStatus);
            });
        });
    }
    
    // Verifica se há itens visíveis para o status atual e exibe mensagem se não houver
    function checkNoVisibleItems(status) {
        const visibleItems = document.querySelectorAll(`.item-container[style="display: "]`);
        const noItemsContainer = document.querySelector('.no-visible-items');
        
        if (visibleItems.length === 0) {
            // Se não existir, cria o container para a mensagem
            if (!noItemsContainer) {
                const itemsRow = document.querySelector('.row');
                const noVisibleItemsDiv = document.createElement('div');
                noVisibleItemsDiv.className = 'no-visible-items no-items w-100';
                noVisibleItemsDiv.innerHTML = `
                    <i class="fas fa-filter d-block"></i>
                    <p>Nenhum item ${status !== 'todos' ? `com status "${status}"` : ''} encontrado.</p>
                `;
                itemsRow.appendChild(noVisibleItemsDiv);
            } else {
                noItemsContainer.style.display = '';
                const statusText = status !== 'todos' ? `com status "${status}"` : '';
                noItemsContainer.querySelector('p').innerText = `Nenhum item ${statusText} encontrado.`;
            }
        } else if (noItemsContainer) {
            noItemsContainer.style.display = 'none';
        }
    }
    
    // Função para trocar a foto principal ao clicar em uma miniatura
    function changeMainPhoto(itemId, photoUrl, thumbnailElement) {
        // Atualiza a foto principal
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (mainPhotoImg) {
            mainPhotoImg.src = photoUrl;
        }
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = thumbnailElement.parentElement.querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnailElement.classList.add('active');
    }
    
    // Função para navegar para a foto anterior
    function prevPhoto(itemId) {
        if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
        
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (!mainPhotoImg) return;
        
        // Encontra o índice atual da foto
        const currentPhotoUrl = mainPhotoImg.src;
        const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
        
        // Calcula o índice anterior (com loop circular)
        const prevIndex = (currentIndex - 1 + itemGalleries[itemId].length) % itemGalleries[itemId].length;
        
        // Atualiza a foto principal
        mainPhotoImg.src = itemGalleries[itemId][prevIndex];
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnails[prevIndex].classList.add('active');
    }
    
    // Função para navegar para a próxima foto
    function nextPhoto(itemId) {
        if (!itemGalleries[itemId] || itemGalleries[itemId].length <= 1) return;
        
        const mainPhotoContainer = document.getElementById(`main-photo-${itemId}`);
        const mainPhotoImg = mainPhotoContainer.querySelector('img');
        if (!mainPhotoImg) return;
        
        // Encontra o índice atual da foto
        const currentPhotoUrl = mainPhotoImg.src;
        const currentIndex = itemGalleries[itemId].findIndex(url => url === currentPhotoUrl);
        
        // Calcula o próximo índice (com loop circular)
        const nextIndex = (currentIndex + 1) % itemGalleries[itemId].length;
        
        // Atualiza a foto principal
        mainPhotoImg.src = itemGalleries[itemId][nextIndex];
        
        // Atualiza a classe ativa na miniatura
        const thumbnails = mainPhotoContainer.parentElement.querySelector('.photo-thumbnails').querySelectorAll('.photo-thumbnail');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        thumbnails[nextIndex].classList.add('active');
    }
</script>

@foreach($user->itens as $item)
    @if($item->status === 'aprovado' && !$item->parceiro_id)
    <!-- Modal Enviar para Parceiro -->
    <div class="modal fade" id="enviarParaParceiroModal-{{ $item->id }}" tabindex="-1" aria-labelledby="enviarParaParceiroModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enviarParaParceiroModalLabel-{{ $item->id }}">Enviar Item para Ponto de Coleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('item.enviar-para-parceiro', ['item' => $item->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div id="map-{{ $item->id }}" style="height: 300px;" class="mb-3"></div>
                            <label for="parceiro_id-{{ $item->id }}" class="form-label">Selecione o Ponto de Coleta</label>
                            <select class="form-select" name="parceiro_id" id="parceiro_id-{{ $item->id }}" required>
                                <option value="">Selecione um ponto de coleta</option>
                                @foreach($parceiros as $parceiro)
                                    <option value="{{ $parceiro->id }}" 
                                            data-lat="{{ $parceiro->localizacao->latitude }}" 
                                            data-lng="{{ $parceiro->localizacao->longitude }}">
                                        {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações (opcional)</label>
                            <textarea class="form-control" name="observacoes" id="observacoes-{{ $item->id }}" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Confirmar Envio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
    <!-- Modal Marcar como Devolvido -->
    <div class="modal fade" id="marcarDevolvidoModal-{{ $item->id }}" tabindex="-1" aria-labelledby="marcarDevolvidoModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="marcarDevolvidoModalLabel-{{ $item->id }}"><i class="fas fa-handshake me-2"></i>Marcar Item como Devolvido</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('item.marcar-como-devolvido', ['item' => $item->id]) }}" method="POST" id="form-devolvido-{{ $item->id }}">
                        @csrf
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Importante:</strong> Ao marcar um item como devolvido, ele não aparecerá mais nas buscas públicas.
                        </div>

                        <div class="mb-4">
                            <p class="fw-bold mb-2">Como este item foi devolvido?</p>
                            
                            <div class="form-check mb-3 p-2 border-start border-3 border-primary rounded-start" style="background-color: #f8f9fa;">
                                <input class="form-check-input" type="radio" name="metodo_devolucao" id="metodo-contato-direto-{{ $item->id }}" value="contato_direto" checked>
                                <label class="form-check-label" for="metodo-contato-direto-{{ $item->id }}">
                                    <strong>Contato direto</strong> - Um usuário entrou em contato comigo via chat e devolveu o item
                                </label>
                            </div>
                            
                            <div class="form-check mb-3 p-2 border-start border-3 border-primary rounded-start" style="background-color: #f8f9fa;">
                                <input class="form-check-input" type="radio" name="metodo_devolucao" id="metodo-encontrado-{{ $item->id }}" value="encontrado">
                                <label class="form-check-label" for="metodo-encontrado-{{ $item->id }}">
                                    <strong>Item encontrado</strong> - Eu mesmo encontrei ou alguém me devolveu diretamente
                                </label>
                            </div>
                            
                            <div class="form-check mb-3 p-2 border-start border-3 border-primary rounded-start" style="background-color: #f8f9fa;">
                                <input class="form-check-input" type="radio" name="metodo_devolucao" id="metodo-parceiro-{{ $item->id }}" value="parceiro">
                                <label class="form-check-label" for="metodo-parceiro-{{ $item->id }}">
                                    <strong>Ponto de coleta</strong> - Retirei o item em um ponto de coleta parceiro
                                </label>
                            </div>
                            
                            <!-- Campos para contato direto -->
                            <div id="contato-direto-campos-{{ $item->id }}" class="mt-3 p-3 border rounded" style="background-color: #f8f9fa;">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Usuário que devolveu o item</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control usuario-autocomplete" 
                                               id="usuario-busca-{{ $item->id }}" 
                                               placeholder="Buscar usuário pelo nome ou email...">
                                        <input type="hidden" name="usuario_id" id="usuario-id-{{ $item->id }}">
                                        <button class="btn btn-outline-secondary" type="button" id="limpar-usuario-{{ $item->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Informe o usuário que devolveu o item para que ele receba uma notificação para confirmar a devolução. Este campo é <strong>obrigatório</strong> para o método de contato direto.</div>
                                </div>
                            </div>
                            
                            <!-- Campos para parceiro -->
                            <div id="parceiro-campos-{{ $item->id }}" class="mt-3 p-3 border rounded d-none" style="background-color: #f8f9fa;">
                                <div class="mb-3">
                                    <label for="parceiro_id-{{ $item->id }}" class="form-label fw-bold">Ponto de coleta onde retirei</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-store"></i></span>
                                        <select class="form-select" name="parceiro_id" id="parceiro_id-{{ $item->id }}">
                                            <option value="">Selecione um ponto de coleta</option>
                                            @foreach($parceiros as $parceiro)
                                                <option value="{{ $parceiro->id }}">
                                                    {{ $parceiro->nome_estabelecimento }} - {{ $parceiro->localizacao->endereco ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="detalhes-{{ $item->id }}" class="form-label fw-bold">Detalhes adicionais (opcional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                <textarea class="form-control" name="detalhes" id="detalhes-{{ $item->id }}" rows="3" 
                                          placeholder="Descreva como ocorreu a devolução, se necessário..."></textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-success" id="btn-confirmar-devolucao-{{ $item->id }}">
                                <i class="fas fa-check me-1"></i>Confirmar Devolução
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa os mapas para os modais de parceiros
    @foreach($user->itens as $item)
        @if($item->status === 'aprovado' && !$item->parceiro_id)
            // Inicializa o mapa para cada item
            const map{{ $item->id }} = new google.maps.Map(document.getElementById('map-{{ $item->id }}'), {
                center: { lat: -20.4697, lng: -54.6201 },
                zoom: 13
            });

            const markers{{ $item->id }} = {};
            
            // Adiciona marcadores para cada parceiro
            @foreach($parceiros as $parceiro)
                markers{{ $item->id }}[{{ $parceiro->id }}] = new google.maps.Marker({
                    position: { 
                        lat: {{ $parceiro->localizacao->latitude }}, 
                        lng: {{ $parceiro->localizacao->longitude }}
                    },
                    map: map{{ $item->id }},
                    title: '{{ $parceiro->nome_estabelecimento }}'
                });

                // Adiciona info window para cada marcador
                const infoWindow{{ $item->id }}{{ $parceiro->id }} = new google.maps.InfoWindow({
                    content: `
                        <strong>{{ $parceiro->nome_estabelecimento }}</strong><br>
                        {{ $parceiro->localizacao->endereco }}<br>
                        <small>{{ $parceiro->horario_funcionamento }}</small>
                    `
                });

                markers{{ $item->id }}[{{ $parceiro->id }}].addListener('click', () => {
                    infoWindow{{ $item->id }}{{ $parceiro->id }}.open(map{{ $item->id }}, markers{{ $item->id }}[{{ $parceiro->id }}]);
                });
            @endforeach

            // Atualiza o mapa quando um parceiro é selecionado
            document.getElementById('parceiro_id-{{ $item->id }}').addEventListener('change', function(e) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                if (selectedOption.value) {
                    const lat = parseFloat(selectedOption.dataset.lat);
                    const lng = parseFloat(selectedOption.dataset.lng);
                    map{{ $item->id }}.setCenter({ lat, lng });
                    map{{ $item->id }}.setZoom(15);
                    markers{{ $item->id }}[selectedOption.value].setAnimation(google.maps.Animation.BOUNCE);
                    setTimeout(() => {
                        markers{{ $item->id }}[selectedOption.value].setAnimation(null);
                    }, 1500);
                }
            });
        @endif
    @endforeach
    
    // Debug para verificar se o jQuery está carregado corretamente
    console.log('jQuery version:', $.fn.jquery);
    console.log('jQuery UI version:', $.ui ? $.ui.version : 'not loaded');
    
    // Inicializa o autocomplete para os campos de email com debug
    $('.email-autocomplete').each(function() {
        console.log('Initializing autocomplete for:', this.id);
        
        // Configura o autocomplete
        var autocompleteWidget = $(this).autocomplete({
            source: function(request, response) {
                console.log('Autocomplete search for:', request.term);
                
                $.ajax({
                    url: "{{ route('usuarios.search') }}",
                    method: 'GET',
                    dataType: "json",
                    data: {
                        query: request.term
                    },
                    success: function(data) {
                        console.log('Autocomplete results:', data);
                        
                        if (data.length === 0) {
                            // Se não houver resultados, mostra uma mensagem
                            response([{ label: 'Nenhum usuário encontrado', value: '' }]);
                        } else {
                            response($.map(data, function(item) {
                                return {
                                    label: item.name + ' (' + item.email + ')',
                                    value: item.email
                                };
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Autocomplete error:', status, error);
                        console.log('Response:', xhr.responseText);
                        response([]);
                    }
                });
            },
            minLength: 2,
            delay: 300,
            position: { my: "left top", at: "left bottom", collision: "flip" },
            appendTo: $(this).closest('.modal-content'),
            open: function(event, ui) {
                console.log('Autocomplete dropdown opened');
                // Ajusta o z-index do dropdown para garantir que ele fique acima do modal
                $('.ui-autocomplete').css('z-index', 9999);
            },
            select: function(event, ui) {
                console.log('Selected:', ui.item);
                if (ui.item.value) { // Verifica se não é o item "Nenhum usuário encontrado"
                    $(this).val(ui.item.value);
                }
                return false;
            }
        }).on('focus', function() {
            // Força a exibição do menu se houver pelo menos 2 caracteres
            if ($(this).val().length >= 2) {
                $(this).autocomplete('search');
            }
        });
        
        // Força o widget a usar o contêiner do modal como referência para posicionamento
        autocompleteWidget.autocomplete('widget').css('z-index', 10000);
    });
    
    // Inicializa os controles para os modais de devolução
    @foreach($user->itens as $item)
        @if($item->status === 'aprovado' || $item->status === 'em_estabelecimento')
            // Controle de exibição dos campos baseado no método de devolução
            const metodoContatoDireto{{ $item->id }} = document.getElementById('metodo-contato-direto-{{ $item->id }}');
            const metodoEncontrado{{ $item->id }} = document.getElementById('metodo-encontrado-{{ $item->id }}');
            const metodoParceiro{{ $item->id }} = document.getElementById('metodo-parceiro-{{ $item->id }}');
            
            const contatoDiretoCampos{{ $item->id }} = document.getElementById('contato-direto-campos-{{ $item->id }}');
            const parceiroCampos{{ $item->id }} = document.getElementById('parceiro-campos-{{ $item->id }}');
            const parceiroId{{ $item->id }} = document.getElementById('parceiro_id-{{ $item->id }}');
            
            // Inicializa o autocomplete para busca de usuários
            const usuarioBusca{{ $item->id }} = document.getElementById('usuario-busca-{{ $item->id }}');
            const usuarioId{{ $item->id }} = document.getElementById('usuario-id-{{ $item->id }}');
            const limparUsuario{{ $item->id }} = document.getElementById('limpar-usuario-{{ $item->id }}');
            
            // Formulário de devolução
            const formDevolvido{{ $item->id }} = document.getElementById('form-devolvido-{{ $item->id }}');
            const btnConfirmarDevolucao{{ $item->id }} = document.getElementById('btn-confirmar-devolucao-{{ $item->id }}');
            
            if (metodoContatoDireto{{ $item->id }} && metodoEncontrado{{ $item->id }} && metodoParceiro{{ $item->id }}) {
                // Evento para método contato direto
                metodoContatoDireto{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        contatoDiretoCampos{{ $item->id }}.classList.remove('d-none');
                        parceiroCampos{{ $item->id }}.classList.add('d-none');
                        parceiroId{{ $item->id }}.removeAttribute('required');
                    }
                });
                
                // Evento para método item encontrado
                metodoEncontrado{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        contatoDiretoCampos{{ $item->id }}.classList.add('d-none');
                        parceiroCampos{{ $item->id }}.classList.add('d-none');
                        parceiroId{{ $item->id }}.removeAttribute('required');
                    }
                });
                
                // Evento para método parceiro
                metodoParceiro{{ $item->id }}.addEventListener('change', function() {
                    if (this.checked) {
                        contatoDiretoCampos{{ $item->id }}.classList.add('d-none');
                        parceiroCampos{{ $item->id }}.classList.remove('d-none');
                        parceiroId{{ $item->id }}.setAttribute('required', 'required');
                    }
                });
                
                // Adiciona evento de submissão ao formulário
                if (formDevolvido{{ $item->id }}) {
                    formDevolvido{{ $item->id }}.addEventListener('submit', function(e) {
                        // Desabilita o botão para evitar múltiplos envios
                        if (btnConfirmarDevolucao{{ $item->id }}) {
                            btnConfirmarDevolucao{{ $item->id }}.disabled = true;
                            btnConfirmarDevolucao{{ $item->id }}.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processando...';
                        }
                    });
                }
                
                // Botão para limpar usuário selecionado
                if (limparUsuario{{ $item->id }}) {
                    limparUsuario{{ $item->id }}.addEventListener('click', function() {
                        usuarioBusca{{ $item->id }}.value = '';
                        usuarioId{{ $item->id }}.value = '';
                    });
                }
                
                // Verificar se o usuário foi selecionado antes de enviar o formulário
                if (formDevolvido{{ $item->id }}) {
                    formDevolvido{{ $item->id }}.addEventListener('submit', function(e) {
                        // Se o método selecionado for contato direto, verificar se um usuário foi selecionado
                        if (metodoContatoDireto{{ $item->id }}.checked && usuarioId{{ $item->id }}.value === '') {
                            e.preventDefault();
                            alert('Por favor, selecione o usuário que devolveu o item para que ele possa confirmar a devolução.');
                            return false;
                        }
                    });
                }
                
                // Configurar autocomplete para busca de usuários
                if (usuarioBusca{{ $item->id }}) {
                    $(usuarioBusca{{ $item->id }}).autocomplete({
                        source: function(request, response) {
                            $.ajax({
                                url: "{{ route('usuarios.search') }}",
                                method: 'GET',
                                dataType: "json",
                                data: {
                                    query: request.term
                                },
                                success: function(data) {
                                    if (data.length === 0) {
                                        response([{ label: 'Nenhum usuário encontrado', value: '' }]);
                                    } else {
                                        response($.map(data, function(item) {
                                            return {
                                                label: item.name + ' (' + item.email + ')',
                                                value: item.name,
                                                id: item.id
                                            };
                                        }));
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Erro na busca de usuários:', status, error);
                                    response([]);
                                }
                            });
                        },
                        minLength: 2,
                        select: function(event, ui) {
                            if (ui.item.id) {
                                usuarioId{{ $item->id }}.value = ui.item.id;
                            }
                            return true;
                        }
                    });
                }
            }
        @endif
    @endforeach
});
</script>
@endpush

</body>
</html>
<!-- Modal de Confirmação de Exclusão de Conta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Excluir Conta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Você tem certeza que deseja excluir sua conta?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Importante:</strong> Sua conta será desativada e programada para exclusão em 30 dias. Durante esse período, você poderá cancelar a exclusão a qualquer momento.
                </div>
                <p class="text-muted small">Todos os seus dados e itens cadastrados ficarão indisponíveis após a exclusão definitiva.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('usuario.desativar-conta') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection