@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header com Boas-vindas -->
    <div class="row bg-primary py-4 mb-4 rounded-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 text-white">Bem-vindo(a), {{ Auth::user()->name }}</h2>
                    <p class="mb-0 text-white-50">Gerencie seus itens e encontre o que procura</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Menu Lateral -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('usuario.home') }}" class="list-group-item list-group-item-action active d-flex align-items-center">
                            <i class="fas fa-home me-3"></i> 
                            <span>Início</span>
                        </a>
                        <a href="{{ route('usuario.cadastrar-item') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-plus-circle me-3"></i>
                            <span>Cadastrar Item</span>
                        </a>
                        <a href="{{ route('listar-todos-itens') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-list me-3"></i>
                            <span>Ver Itens</span>
                        </a>
                        <a href="{{ url('/chatify') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-comments me-3"></i>
                            <span>Chat</span>
                            @php
                                $unreadChatMessages = Auth::user()->unreadNotifications
                                    ->where('data.type', 'chat_message')
                                    ->count();
                            @endphp
                            @if($unreadChatMessages > 0)
                                <span class="badge bg-primary rounded-pill ms-auto">{{ $unreadChatMessages }}</span>
                            @endif
                        </a>
                        <a href="{{ route('itens.mapa') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-3"></i>
                            <span>Mapa</span>
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
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="action-icon me-3">
                                    <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Cadastrar Novo Item</h5>
                                    <p class="card-text text-muted mb-0">Registre um item perdido ou encontrado</p>
                                </div>
                            </div>
                            <a href="{{ route('usuario.cadastrar-item') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Cadastrar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="action-icon me-3">
                                    <i class="fas fa-search fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Buscar Itens</h5>
                                    <p class="card-text text-muted mb-0">Encontre itens perdidos ou achados</p>
                                </div>
                            </div>
                            <a href="{{ route('listar-todos-itens') }}" class="btn btn-success w-100">
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
                        <h5 class="mb-0">Meus Itens Recentes</h5>
                        <a href="{{ route('perfil-usuario') }}" class="btn btn-outline-primary btn-sm">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($itens) && count($itens) > 0)
                        <div class="row">
                            @foreach($itens->take(3) as $item)
                                <div class="col-md-4 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="position-relative">
                                            @if($item->fotos && $item->fotos->isNotEmpty())
                                                @php
                                                    $fotoPrincipal = $item->fotos->where('is_principal', true)->first();
                                                    $foto = $fotoPrincipal ?? $item->fotos->first();
                                                @endphp
                                                @if($foto && $foto->caminho)
                                                    <img src="{{ asset('storage/' . $foto->caminho) }}" class="card-img-top" alt="Item" style="height: 200px; object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100" style="height: 200px; background-color: #f8f9fa;">
                                                        <i class="fas fa-image text-muted fa-3x"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100" style="height: 200px; background-color: #f8f9fa;">
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                </div>
                                            @endif
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-{{ $item->status === 'pendente' ? 'warning' : 'success' }} rounded-pill">
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
                                                    <a href="{{ route('itens.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('usuario.editar-item', $item->id) }}" class="btn btn-sm btn-outline-warning">
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

<style>
    .card {
        border: none;
        transition: all 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
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
    .list-group-item i {
        width: 20px;
        text-align: center;
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
    .empty-state {
        opacity: 0.5;
    }
</style>
@endsection