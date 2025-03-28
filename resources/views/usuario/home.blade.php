@extends('layouts.app')

@section('content')
        <div class="container-fluid">
    <!-- Header com Boas-vindas e Notificações -->
    <div class="row bg-primary py-3 mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 text-white">Bem-vindo(a), {{ Auth::user()->name }}</h2>
                    <p class="mb-0 text-white-50">Gerencie seus itens e encontre o que procura</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="position-relative me-3">
                        <a href="#" class="text-white notification-btn" data-bs-toggle="modal" data-bs-target="#notificacoesModal">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-white dropdown-toggle user-menu" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg"></i>
            </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('perfil-usuario') }}"><i class="fas fa-user me-2"></i>Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Sair</button>
                                </form>
                    </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Menu Lateral -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('usuario.home') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-home me-2"></i> Início
                        </a>
                        <a href="{{ route('usuario.cadastrar-item') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2"></i> Cadastrar Item
                        </a>
                        <a href="{{ route('listar-todos-itens') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-list me-2"></i> Ver Itens
                        </a>
                        <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-comments me-2"></i> Chat
                            <span class="badge bg-primary rounded-pill float-end">5</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marker-alt me-2"></i> Mapa
                        </a>
                        <a href="{{ route('perfil-usuario') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Perfil
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i> Configurações
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9">
            <!-- Ações Rápidas -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="action-icon me-3">
                                    <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Cadastrar Novo Item</h5>
                                    <p class="card-text text-muted mb-0">Registre um item perdido ou encontrado</p>
                                </div>
                            </div>
                            <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary w-100 mt-3">
                                <i class="fas fa-plus me-2"></i>Cadastrar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="action-icon me-3">
                                    <i class="fas fa-search fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Buscar Itens</h5>
                                    <p class="card-text text-muted mb-0">Encontre itens perdidos ou achados</p>
                                </div>
                            </div>
                            <a href="{{ route('listar-todos-itens') }}" class="btn btn-success w-100 mt-3">
                                <i class="fas fa-search me-2"></i>Buscar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção de Itens -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Meus Itens</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary active">Todos</button>
                            <button type="button" class="btn btn-outline-primary">Perdidos</button>
                            <button type="button" class="btn btn-outline-primary">Achados</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($itens) && count($itens) > 0)
                        <div class="row">
                            @foreach($itens as $item)
                                <div class="col-md-4 mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="position-relative">
                                            <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="Item">
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-{{ $item->status === 'pendente' ? 'warning' : 'success' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title mb-2">{{ $item->descricao }}</h6>
                                            <p class="card-text small text-muted mb-3">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $item->created_at->format('d/m/Y') }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state mb-4">
                                <i class="fas fa-box-open fa-3x text-muted"></i>
                            </div>
                            <p class="text-muted mb-4">Você ainda não cadastrou nenhum item.</p>
                            <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Cadastrar Primeiro Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Notificações -->
<div class="modal fade" id="notificacoesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Notificações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Novo match encontrado</h6>
                            <small class="text-muted">3h atrás</small>
                        </div>
                        <p class="mb-1">Possível correspondência para seu iPhone</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Mensagens -->
<div class="modal fade" id="mensagensModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title">Mensagens</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">João Silva</h6>
                            <small class="text-muted">2h atrás</small>
                        </div>
                        <p class="mb-1">Olá, encontrei um item que pode ser seu...</p>
                    </a>
                    <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action bg-light text-center py-3">
                        <i class="fas fa-comments me-2"></i> Abrir Chat Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        transition: all 0.2s ease;
    }
    .action-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, 0.1);
        border-radius: 10px;
    }
    .list-group-item {
        border: none;
        padding: 1rem;
        transition: all 0.2s ease;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    .notification-btn, .user-menu {
        transition: all 0.2s ease;
    }
    .notification-btn:hover, .user-menu:hover {
        transform: scale(1.1);
    }
    .empty-state {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(108, 117, 125, 0.1);
        border-radius: 50%;
    }
    .card-img-top {
        height: 180px;
        object-fit: cover;
    }
</style>
@endsection